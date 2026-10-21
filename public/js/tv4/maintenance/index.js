
  setTimeout(function () { document.body.classList.remove('skeleton-mode'); }, 2500);

  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 500);

    // Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
  });
