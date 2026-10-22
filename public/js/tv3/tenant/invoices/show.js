
  // 1. Tắt Skeleton Loading sau 0.8s (Mô phỏng load dữ liệu)
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
      document.body.classList.remove('skeleton-mode');
    }, 800); 
  });

  // 2. Chức năng hiển thị Toast khi bấm Copy
  function showCopyToast(text) {
    const toast = document.getElementById('copyToast');
    if (!toast) return;
    if (text) document.getElementById('copyToastText').textContent = text;
    toast.classList.add('show');

    // Tự động tắt sau 3 giây
    setTimeout(() => {
      toast.classList.remove('show');
    }, 3000);
  }

  // 3. Sao chép số tiền & nội dung chuyển khoản
  function copyPayInfo() {
    const text = "{{ number_format((float) $invoice->total) }} {{ $invoice->invoice_code }} {{ $invoice->contract->room->name ?? '' }}";
    const done = () => showCopyToast('Đã sao chép số tiền & nội dung!');
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(done, () => showCopyToast(text));
    } else {
      showCopyToast(text);
    }
  }
