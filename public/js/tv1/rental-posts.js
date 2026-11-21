
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

document.addEventListener('submit', (event) => {
    const form = event.target.closest('[data-confirm-status]');
    if (!form) return;

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
