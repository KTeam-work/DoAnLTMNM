
  // Fallback: luôn hiện nội dung sau 2.5s kể cả khi JS phía dưới lỗi
  setTimeout(function () { document.body.classList.remove('skeleton-mode'); }, 2500);

  document.addEventListener("DOMContentLoaded", function() {
    // Tắt skeleton
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 500);

    // Tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
  });
