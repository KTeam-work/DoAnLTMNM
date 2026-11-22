// Fallback: luôn hiện nội dung sau 2.5s kể cả khi JS phía dưới lỗi
setTimeout(function () { document.body.classList.remove('skeleton-mode'); }, 2500);

function initPaymentAjaxFilters() {
  // Tắt skeleton
  document.body.classList.remove('skeleton-mode');

  // Tooltip
  const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  [...tooltipTriggerList].forEach(el => new bootstrap.Tooltip(el));

  const form = document.getElementById('paymentFilterForm');
  if (!form || form.dataset.ajaxInitialized === 'true') return;
  form.dataset.ajaxInitialized = 'true';

  let isFetching = false;

  async function applyFilterAjax(url) {
    if (isFetching) return;
    isFetching = true;

    const tableWrapper = document.getElementById('paymentTableWrapper');
    const statWrapper = document.getElementById('paymentStatCards');
    const footerWrapper = document.getElementById('paymentFooterWrapper');

    try {
      const res = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'text/html'
        }
      });

      if (!res.ok) {
        window.location.href = url;
        return;
      }

      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');

      // 1. Cập nhật Stat Cards
      const newStats = doc.getElementById('paymentStatCards');
      if (newStats && statWrapper) {
        statWrapper.innerHTML = newStats.innerHTML;
      }

      // 2. Cập nhật Bảng giao dịch
      const newTable = doc.getElementById('paymentTableWrapper');
      if (newTable && tableWrapper) {
        tableWrapper.innerHTML = newTable.innerHTML;
      }

      // 3. Cập nhật Footer / Pagination
      const newFooter = doc.getElementById('paymentFooterWrapper');
      if (newFooter && footerWrapper) {
        footerWrapper.innerHTML = newFooter.innerHTML;
      }

      // 4. Cập nhật nút Xóa lọc
      const resetWrapper = document.getElementById('paymentResetWrapper');
      const newReset = doc.getElementById('paymentResetWrapper');
      if (newReset && resetWrapper) {
        resetWrapper.innerHTML = newReset.innerHTML;
      }

      // 5. Cập nhật URL trên thanh địa chỉ không cần tải lại trang
      window.history.pushState({}, '', url);

      // Rebind tooltips
      const newTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      [...newTooltips].forEach(el => new bootstrap.Tooltip(el));

    } catch (e) {
      console.error('AJAX Filter error:', e);
      window.location.href = url;
    } finally {
      isFetching = false;
    }
  }

  function onFilterChange() {
    const formData = new FormData(form);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
      if (value) params.append(key, value);
    }
    const targetUrl = form.action + (params.toString() ? '?' + params.toString() : '');
    applyFilterAjax(targetUrl);
  }

  form.querySelectorAll('input, select').forEach(el => {
    el.onchange = onFilterChange;
  });

  form.onsubmit = function(e) {
    e.preventDefault();
    onFilterChange();
  };

  // Lắng nghe click vào nút Xóa lọc và các link phân trang
  document.addEventListener('click', function(e) {
    const resetLink = e.target.closest('#resetPaymentFilterBtn');
    if (resetLink) {
      e.preventDefault();
      form.querySelectorAll('input, select').forEach(el => el.value = '');
      applyFilterAjax(resetLink.href);
      return;
    }

    const paginationLink = e.target.closest('#paymentFooterWrapper .pagination a');
    if (paginationLink) {
      e.preventDefault();
      applyFilterAjax(paginationLink.href);
      return;
    }
  });
}

window.initPaymentAjaxFilters = initPaymentAjaxFilters;

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initPaymentAjaxFilters);
} else {
  initPaymentAjaxFilters();
}
