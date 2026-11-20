/**
 * SEPAY QR PAYMENT HANDLER FOR TENANT
 * Hỗ trợ mở modal QR, hiển thị mã thanh toán SePay và tự động kiểm tra gạch nợ thời gian thực (Polling).
 */

(function () {
    let currentInvoiceId = null;
    let currentRawAmount = 0;
    let pollInterval = null;
    let isPolling = false;

    // Lấy CSRF token từ meta tag
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Hiển thị toast thông báo
    function showToast(message) {
        const toastEl = document.getElementById('sepayToast');
        const msgEl = document.getElementById('sepayToastMessage');
        if (!toastEl || !msgEl) return;

        msgEl.textContent = message;
        if (window.bootstrap && window.bootstrap.Toast) {
            const toast = new bootstrap.Toast(toastEl, { delay: 2500 });
            toast.show();
        } else {
            alert(message);
        }
    }

    // Hàm sao chép dữ liệu từ ID phần tử
    window.copyModalField = function (elementId, message) {
        const el = document.getElementById(elementId);
        if (!el) return;
        const text = el.textContent.trim();
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showToast(message || 'Đã sao chép!');
            }).catch(() => {
                showToast('Không thể sao chép tự động!');
            });
        } else {
            showToast(message || 'Đã sao chép!');
        }
    };

    // Hàm sao chép số tiền số nguyên
    window.copyRawAmount = function () {
        if (!currentRawAmount) return;
        const text = Math.round(currentRawAmount).toString();
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Đã chép số tiền ' + text + ' đ!');
            });
        } else {
            showToast('Đã chép số tiền!');
        }
    };

    // Hàm mở modal thanh toán QR
    window.openSepayQrModal = function (invoiceId, invoiceCode) {
        currentInvoiceId = invoiceId;
        stopSepayPolling();

        const modalEl = document.getElementById('sepayQrModal');
        if (!modalEl) {
            console.error('Không tìm thấy #sepayQrModal trên trang.');
            return;
        }

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        // Cập nhật giao diện ban đầu
        document.getElementById('modalInvoiceCodeText').textContent = invoiceCode || '#' + invoiceId;
        document.getElementById('modalQrLoading').classList.remove('d-none');
        document.getElementById('modalQrError').classList.add('d-none');
        document.getElementById('modalQrContent').classList.add('d-none');
        document.getElementById('modalPollingWaiting').classList.remove('d-none');
        document.getElementById('modalPollingSuccess').classList.add('d-none');
        document.getElementById('btnManualCheckStatus').disabled = false;

        // Gọi backend tạo hoặc lấy mã QR pending
        fetch(`/tenant/invoices/${invoiceId}/pay-qr`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status !== 200 || !body.success) {
                throw new Error(body.message || 'Không thể tạo mã thanh toán QR');
            }

            const data = body.data;
            currentRawAmount = data.amount;

            // Đổ dữ liệu vào modal
            document.getElementById('modalInvoiceCodeText').textContent = data.invoice_code || '#' + invoiceId;
            document.getElementById('modalBankNameText').textContent = data.bank_name;
            document.getElementById('modalAccountNameText').textContent = data.account_name;
            document.getElementById('modalAccountNumText').textContent = data.bank_account;
            document.getElementById('modalAmountText').textContent = data.amount_formatted;
            document.getElementById('modalPaymentCodeText').textContent = data.payment_code;

            const qrImg = document.getElementById('modalQrImg');
            qrImg.src = data.qr_url;

            // Chuyển sang hiển thị nội dung
            document.getElementById('modalQrLoading').classList.add('d-none');
            document.getElementById('modalQrContent').classList.remove('d-none');

            // Nếu hóa đơn đã được thanh toán
            if (data.invoice_status === 'paid' || data.status === 'success') {
                showSuccessState();
            } else {
                startSepayPolling(invoiceId);
            }
        })
        .catch(err => {
            console.error('Lỗi khởi tạo QR:', err);
            document.getElementById('modalQrLoading').classList.add('d-none');
            document.getElementById('modalQrError').classList.remove('d-none');
            document.getElementById('modalQrErrorMessage').textContent = err.message || 'Đã có lỗi xảy ra khi tạo giao dịch QR.';
        });
    };

    // Bắt đầu polling kiểm tra trạng thái
    function startSepayPolling(invoiceId) {
        stopSepayPolling();
        isPolling = true;

        pollInterval = setInterval(() => {
            if (!isPolling) return;
            checkPaymentStatus(invoiceId, false);
        }, 3500);
    }

    // Dừng polling
    function stopSepayPolling() {
        isPolling = false;
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    // Kiểm tra trạng thái hóa đơn
    function checkPaymentStatus(invoiceId, isManual) {
        if (!invoiceId) return;

        const btnManual = document.getElementById('btnManualCheckStatus');
        if (isManual && btnManual) {
            btnManual.disabled = true;
            btnManual.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang kiểm tra...';
        }

        fetch(`/tenant/invoices/${invoiceId}/payment-status`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.json())
        .then(res => {
            if (isManual && btnManual) {
                btnManual.disabled = false;
                btnManual.innerHTML = '🔄 Kiểm tra lại';
            }

            if (res.success && res.data) {
                const data = res.data;
                if (data.is_paid || data.invoice_status === 'paid' || data.payment_status === 'success') {
                    stopSepayPolling();
                    showSuccessState();
                } else if (isManual) {
                    showToast('Hệ thống chưa nhận được tiền. Nếu bạn vừa chuyển, vui lòng đợi 3-5 giây rồi thử lại.');
                }
            }
        })
        .catch(err => {
            console.error('Lỗi polling trạng thái:', err);
            if (isManual && btnManual) {
                btnManual.disabled = false;
                btnManual.innerHTML = '🔄 Kiểm tra lại';
            }
        });
    }

    // Hiển thị trạng thái thành công
    function showSuccessState() {
        document.getElementById('modalPollingWaiting').classList.add('d-none');
        document.getElementById('modalPollingSuccess').classList.remove('d-none');
        const btnManual = document.getElementById('btnManualCheckStatus');
        if (btnManual) btnManual.classList.add('d-none');

        // Tự động reload sau 2.2 giây để cập nhật toàn bộ trang
        setTimeout(() => {
            window.location.reload();
        }, 2200);
    }

    // Nút kiểm tra thủ công
    window.manualCheckStatus = function () {
        if (currentInvoiceId) {
            checkPaymentStatus(currentInvoiceId, true);
        }
    };

    // Nút thử lại khi lỗi
    window.retryLoadQr = function () {
        if (currentInvoiceId) {
            window.openSepayQrModal(currentInvoiceId);
        }
    };

    // Giả lập giao dịch thanh toán Test Mode trực tiếp từ nút bấm
    window.simulateTestPayment = function () {
        if (!currentInvoiceId) return;

        const btn = document.getElementById('btnSimulateTest');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang giả lập...';
        }

        fetch(`/tenant/invoices/${currentInvoiceId}/simulate-sepay`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '⚡ Giả lập thanh toán ngay (Demo)';
            }
            if (data.success) {
                showToast('Giả lập webhook SePay thành công!');
                showSuccessState();
            } else {
                showToast(data.message || 'Lỗi giả lập thanh toán');
            }
        })
        .catch(err => {
            console.error('Lỗi giả lập SePay:', err);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '⚡ Giả lập thanh toán ngay (Demo)';
            }
            showToast('Không thể kết nối đến máy chủ');
        });
    };

    // Dừng polling khi đóng modal
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('sepayQrModal');
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                stopSepayPolling();
            });
        }

        // Tự động gán sự kiện cho các nút có class .btn-pay-qr
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-pay-qr');
            if (btn) {
                e.preventDefault();
                const invoiceId = btn.getAttribute('data-invoice-id');
                const invoiceCode = btn.getAttribute('data-invoice-code');
                if (invoiceId) {
                    window.openSepayQrModal(invoiceId, invoiceCode);
                }
            }
        });
    });
})();

