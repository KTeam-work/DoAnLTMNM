/**
 * TRO OI - ADMIN USER MANAGEMENT (AJAX & CRISP MODAL - NO BLUR)
 * File: public/js/tv1/users.js
 */

document.addEventListener('DOMContentLoaded', () => {
    const modalBackdrop = document.getElementById('userActionModal');
    const modalTitle = document.getElementById('modalActionTitle');
    const modalBadge = document.getElementById('modalBadgeIcon');
    const modalUserInfo = document.getElementById('modalTargetUserInfo');
    const modalDesc = document.getElementById('modalActionDesc');
    const btnConfirm = document.getElementById('btnModalConfirm');
    const btnCancel = document.getElementById('btnModalCancel');
    const btnClose = document.getElementById('btnModalClose');
    const toastContainer = document.getElementById('customToastContainer');

    let currentActionData = null;

    // Toast notification generator (crisp, zero blur)
    function showToast(title, message, type = 'success') {
        if (!toastContainer) return;

        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;

        const iconSvg = type === 'success'
            ? `<svg class="custom-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`
            : `<svg class="custom-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>`;

        toast.innerHTML = `
            ${iconSvg}
            <div class="custom-toast-content">
                <div class="custom-toast-title">${title}</div>
                <div class="custom-toast-msg">${message}</div>
            </div>
        `;

        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 250);
        }, 3500);
    }

    // Modal Control
    function openModal(data) {
        currentActionData = data;

        if (data.actionType === 'lock') {
            modalBadge.className = 'modal-badge-icon lock';
            modalBadge.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>`;
            modalTitle.textContent = 'Xác nhận khóa tài khoản';
            modalDesc.textContent = 'Tài khoản này sẽ bị khóa quyền truy cập ngay lập tức. Người dùng sẽ nhận được email thông báo kèm liên kết để gửi yêu cầu xin mở khóa.';
            btnConfirm.className = 'btn-modal-confirm btn-danger-confirm';
            btnConfirm.querySelector('.btn-text').textContent = 'Xác nhận khóa';
        } else if (data.actionType === 'approve') {
            modalBadge.className = 'modal-badge-icon approve';
            modalBadge.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>`;
            modalTitle.textContent = 'Duyệt yêu cầu mở khóa';
            modalDesc.textContent = 'Bạn đang duyệt mở khóa cho tài khoản này. Hệ thống sẽ khôi phục trạng thái hoạt động và gửi email kích hoạt đến người dùng.';
            btnConfirm.className = 'btn-modal-confirm btn-warning-confirm';
            btnConfirm.querySelector('.btn-text').textContent = 'Duyệt mở khóa';
        } else {
            modalBadge.className = 'modal-badge-icon unlock';
            modalBadge.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 9.9-1"></path></svg>`;
            modalTitle.textContent = 'Mở khóa tài khoản';
            modalDesc.textContent = 'Khôi phục quyền truy cập cho tài khoản. Người dùng sẽ có thể đăng nhập bình thường ngay sau khi mở khóa.';
            btnConfirm.className = 'btn-modal-confirm btn-success-confirm';
            btnConfirm.querySelector('.btn-text').textContent = 'Xác nhận mở khóa';
        }

        let reasonHtml = '';
        if (data.unlockReason) {
            reasonHtml = `<div class="mt-2 p-2 bg-white rounded border text-muted fst-italic" style="font-size: 12px;"><strong>Lý do giải trình:</strong> "${data.unlockReason}"</div>`;
        }

        modalUserInfo.innerHTML = `
            <div><strong>Người dùng:</strong> <span class="text-dark fw-bold">${data.userName}</span></div>
            <div><strong>Email:</strong> <span class="text-muted">${data.userEmail}</span></div>
            ${reasonHtml}
        `;

        modalBackdrop.classList.add('show');
    }

    function closeModal() {
        modalBackdrop.classList.remove('show');
        currentActionData = null;
        if (btnConfirm) {
            btnConfirm.disabled = false;
        }
    }

    if (btnCancel) btnCancel.addEventListener('click', closeModal);
    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', (e) => {
            if (e.target === modalBackdrop) closeModal();
        });
    }

    // Handle Confirm click via AJAX
    if (btnConfirm) {
        btnConfirm.addEventListener('click', async () => {
            if (!currentActionData || !currentActionData.form) return;

            const form = currentActionData.form;
            const actionUrl = form.getAttribute('action');
            const csrfToken = form.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            btnConfirm.disabled = true;
            btnConfirm.querySelector('.btn-text').textContent = 'Đang xử lý...';

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        '_method': 'PATCH',
                        '_token': csrfToken
                    })
                });

                const data = await response.json();

                if (data.success) {
                    closeModal();
                    showToast('Thành công', data.message || 'Thao tác hoàn tất!', 'success');
                    // Refresh table content seamlessly via AJAX
                    await reloadTableContent(window.location.href);
                } else {
                    showToast('Lỗi', data.message || 'Không thể cập nhật trạng thái.', 'danger');
                    btnConfirm.disabled = false;
                    btnConfirm.querySelector('.btn-text').textContent = 'Xác nhận';
                }
            } catch (err) {
                console.error(err);
                showToast('Lỗi', 'Đã xảy ra lỗi kết nối, vui lòng thử lại.', 'danger');
                btnConfirm.disabled = false;
                btnConfirm.querySelector('.btn-text').textContent = 'Xác nhận';
            }
        });
    }

    // Attach Action Buttons
    function bindActionButtons() {
        document.querySelectorAll('.btn-action-trigger').forEach((btn) => {
            btn.onclick = (e) => {
                e.preventDefault();
                const form = btn.closest('form');
                openModal({
                    userId: btn.dataset.userId,
                    userName: btn.dataset.userName,
                    userEmail: btn.dataset.userEmail,
                    actionType: btn.dataset.actionType,
                    unlockReason: btn.dataset.unlockReason || '',
                    form: form
                });
            };
        });
    }

    // AJAX Table & Filter Reloading (Seamless - No Blur, No Full Page Reload)
    async function reloadTableContent(url) {
        const tableContainer = document.getElementById('usersTableContainer');
        const filterWrapper = document.getElementById('segmentedFilterWrapper');
        const paginationContainer = document.getElementById('usersPaginationContainer');

        if (tableContainer) {
            tableContainer.style.opacity = '0.7';
        }

        try {
            const resp = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const html = await resp.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newTable = doc.getElementById('usersTableContainer');
            const newFilter = doc.getElementById('segmentedFilterWrapper');
            const newPagination = doc.getElementById('usersPaginationContainer');

            if (newTable && tableContainer) {
                tableContainer.innerHTML = newTable.innerHTML;
            }

            if (newFilter && filterWrapper) {
                filterWrapper.innerHTML = newFilter.innerHTML;
            }

            if (newPagination && paginationContainer) {
                paginationContainer.innerHTML = newPagination.innerHTML;
            }

            // Update browser history URL
            window.history.pushState({}, '', url);

            // Rebind all triggers
            bindActionButtons();
            bindFilterEvents();
        } catch (e) {
            console.error('Error loading via AJAX:', e);
            // Fallback to normal navigation if fetch fails
            window.location.href = url;
        } finally {
            if (tableContainer) {
                tableContainer.style.opacity = '1';
            }
        }
    }

    // Bind Segmented Tabs, Search Form & Pagination to AJAX
    function bindFilterEvents() {
        // 1. Segmented Filter Tabs
        document.querySelectorAll('#segmentedFilterWrapper .segmented-tab').forEach((tab) => {
            tab.onclick = (e) => {
                e.preventDefault();
                const href = tab.getAttribute('href');
                if (href) {
                    reloadTableContent(href);
                }
            };
        });

        // 2. Filter Form
        const filterForm = document.querySelector('.tv1-filter-form');
        if (filterForm) {
            filterForm.onsubmit = (e) => {
                e.preventDefault();
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const targetUrl = `${filterForm.getAttribute('action')}?${params.toString()}`;
                reloadTableContent(targetUrl);
            };

            const resetBtn = filterForm.querySelector('.tv1-btn-secondary');
            if (resetBtn) {
                resetBtn.onclick = (e) => {
                    e.preventDefault();
                    filterForm.reset();
                    reloadTableContent(resetBtn.getAttribute('href'));
                };
            }
        }

        // 3. Pagination Links
        document.querySelectorAll('#usersPaginationContainer a').forEach((pageLink) => {
            pageLink.onclick = (e) => {
                e.preventDefault();
                const href = pageLink.getAttribute('href');
                if (href) {
                    reloadTableContent(href);
                }
            };
        });
    }

    // Initial Bind
    bindActionButtons();
    bindFilterEvents();
});
