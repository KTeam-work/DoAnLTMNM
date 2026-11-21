let pendingFormToSubmit = null;

function toggleRejectionReason(select) {
    const form = select.closest('form');
    if (!form) return;

    const label = form.querySelector('[id^="reason-label-"]');
    const textarea = form.querySelector('textarea[name="rejection_reason"]');

    if (select.value === 'rejected') {
        if (label) label.style.display = '';
        if (textarea) {
            textarea.style.display = '';
            textarea.focus();
        }
    } else {
        if (label) label.style.display = 'none';
        if (textarea) textarea.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const statusModalEl = document.getElementById('statusConfirmModal');
    let statusModalInstance = null;
    if (statusModalEl && window.bootstrap && window.bootstrap.Modal) {
        statusModalInstance = new bootstrap.Modal(statusModalEl);
    }

    const labels = {
        draft: 'Bản nháp',
        pending: 'Chờ duyệt',
        published: 'Đã đăng',
        rejected: 'Từ chối',
        hidden: 'Đang ẩn',
    };

    const hints = {
        published: 'Tin đăng sẽ được duyệt và hiển thị công khai trên ứng dụng Trọ Ơi.',
        rejected: 'Tin đăng sẽ bị từ chối và chủ trọ sẽ nhận được lý do từ chối để chỉnh sửa lại.',
        hidden: 'Tin đăng sẽ tạm ẩn và không còn xuất hiện trong kết quả tìm kiếm.',
        pending: 'Tin đăng sẽ được chuyển về hàng đợi để chờ xem xét duyệt lại.',
        draft: 'Tin đăng sẽ được đưa về dạng bản nháp nội bộ.',
    };

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('[data-confirm-status]');
        if (!form) return;

        // Nếu form đã được xác nhận từ modal, cho submit bình thường
        if (form.dataset.confirmed === 'true') {
            delete form.dataset.confirmed;
            return;
        }

        // Chặn submit mặc định để mở modal thông báo đẹp mắt
        event.preventDefault();
        pendingFormToSubmit = form;

        const currentStatus = form.getAttribute('data-current-status') || 'pending';
        const currentLabel = form.getAttribute('data-current-label') || labels[currentStatus] || currentStatus;
        const targetStatus = form.querySelector('[name="status"]')?.value || 'published';
        const targetLabel = labels[targetStatus] ?? targetStatus;
        const postTitle = form.getAttribute('data-post-title') || 'Tin đăng';
        const roomName = form.getAttribute('data-room-name') || '';
        const currentReason = form.querySelector('textarea[name="rejection_reason"]')?.value || '';

        // Cập nhật nội dung modal
        const curBadge = document.getElementById('modalCurrentStatusBadge');
        if (curBadge) {
            curBadge.className = 'rp-status status-' + currentStatus;
            curBadge.textContent = currentLabel;
        }

        const newBadge = document.getElementById('modalNewStatusBadge');
        if (newBadge) {
            newBadge.className = 'rp-status status-' + targetStatus;
            newBadge.textContent = targetLabel;
        }

        const titleEl = document.getElementById('modalPostTitle');
        if (titleEl) titleEl.textContent = postTitle;

        const roomEl = document.getElementById('modalPostRoom');
        if (roomEl) roomEl.textContent = 'Phòng: ' + (roomName || '—');

        const targetNameEl = document.getElementById('modalTargetStatusName');
        if (targetNameEl) targetNameEl.textContent = `"${targetLabel}"`;

        const hintEl = document.getElementById('modalStatusHint');
        if (hintEl) {
            hintEl.textContent = hints[targetStatus] || 'Trạng thái của tin đăng sẽ được cập nhật trên hệ thống.';
        }

        // Xử lý ô lý do từ chối
        const reasonGroup = document.getElementById('modalRejectionReasonGroup');
        const reasonInput = document.getElementById('modalRejectionReasonInput');
        const reasonError = document.getElementById('modalRejectionError');

        if (reasonGroup && reasonInput) {
            if (targetStatus === 'rejected') {
                reasonGroup.style.display = 'block';
                reasonInput.value = currentReason;
                if (reasonError) reasonError.style.display = 'none';
            } else {
                reasonGroup.style.display = 'none';
                reasonInput.value = '';
            }
        }

        // Đổi màu và chữ nút xác nhận theo tính chất hành động
        const confirmBtn = document.getElementById('btnModalConfirmStatus');
        if (confirmBtn) {
            if (targetStatus === 'published') {
                confirmBtn.style.background = '#15803d';
                confirmBtn.style.borderColor = '#15803d';
                confirmBtn.textContent = '✓ Duyệt & Đăng tin';
            } else if (targetStatus === 'rejected') {
                confirmBtn.style.background = '#dc2626';
                confirmBtn.style.borderColor = '#dc2626';
                confirmBtn.textContent = '✕ Xác nhận Từ chối';
            } else {
                confirmBtn.style.background = '#0c2340';
                confirmBtn.style.borderColor = '#0c2340';
                confirmBtn.textContent = '✓ Đồng ý thay đổi';
            }
        }

        if (statusModalInstance) {
            statusModalInstance.show();
        } else {
            // Fallback nếu modal không khả dụng
            if (confirm(`Bạn có chắc muốn chuyển tin sang trạng thái "${targetLabel}"?`)) {
                form.dataset.confirmed = 'true';
                form.submit();
            }
        }
    });

    // Nút xác nhận trong modal
    const confirmBtn = document.getElementById('btnModalConfirmStatus');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', () => {
            if (!pendingFormToSubmit) return;

            const targetStatus = pendingFormToSubmit.querySelector('[name="status"]')?.value;
            const reasonInput = document.getElementById('modalRejectionReasonInput');
            const reasonError = document.getElementById('modalRejectionError');

            if (targetStatus === 'rejected') {
                const reasonVal = reasonInput ? reasonInput.value.trim() : '';
                if (!reasonVal) {
                    if (reasonError) reasonError.style.display = 'block';
                    if (reasonInput) reasonInput.focus();
                    return;
                }
                // Đồng bộ lý do vào textarea của form
                const formReason = pendingFormToSubmit.querySelector('textarea[name="rejection_reason"]');
                if (formReason) formReason.value = reasonVal;
            }

            if (statusModalInstance) {
                statusModalInstance.hide();
            }

            pendingFormToSubmit.dataset.confirmed = 'true';
            pendingFormToSubmit.submit();
        });
    }
});
