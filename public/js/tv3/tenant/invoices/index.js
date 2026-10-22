
  // Tắt Skeleton sau khi trang tải xong
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
      document.body.classList.remove('skeleton-mode');
    }, 800);
  });
