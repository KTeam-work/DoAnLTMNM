
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm-status]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const status = form.querySelector('[name="status"]')?.value;
            const reason = form.querySelector('[name="rejection_reason"]');

            if (status === 'rejected' && !reason?.value.trim()) {
                event.preventDefault();
                reason?.focus();
                alert('Vui lòng nhập lý do từ chối tin đăng.');
                return;
            }

            const labels = {
                draft: 'Bản nháp',
                pending: 'Chờ duyệt',
                published: 'Đã đăng',
                rejected: 'Từ chối',
                hidden: 'Ẩn tin',
            };

            if (!confirm(`Bạn có chắc muốn chuyển tin sang trạng thái "${labels[status] ?? status}"?`)) {
                event.preventDefault();
            }
        });
    });
});
