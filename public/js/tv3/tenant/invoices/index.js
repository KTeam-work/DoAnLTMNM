
  // Tắt Skeleton sau khi trang tải xong
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
      document.body.classList.remove('skeleton-mode');
    }, 800);
  });

  (() => {
    if (window.tenantInvoiceFilterInitialized) return;
    window.tenantInvoiceFilterInitialized = true;
    let pendingRequest;

    async function updateInvoices(url) {
      const results = document.getElementById('invoice-results');
      const form = document.getElementById('invoice-filter-form');
      if (!results || !form) return;
      if (pendingRequest) pendingRequest.abort();
      const request = new AbortController();
      pendingRequest = request;
      const focusId = document.activeElement?.id;
      const error = document.getElementById('invoice-filter-error');
      if (error) error.hidden = true;
      results.setAttribute('aria-busy', 'true');
      results.classList.add('invoice-results-loading');

      try {
        const response = await fetch(url, { signal: request.signal, headers: { 'Accept': 'text/html' } });
        if (!response.ok) throw new Error('Không thể tải hóa đơn');
        const html = await response.text();
        const nextPage = new DOMParser().parseFromString(html, 'text/html');
        const nextResults = nextPage.getElementById('invoice-results');
        const nextForm = nextPage.getElementById('invoice-filter-form');
        if (!nextResults || !nextForm) {
          if (response.redirected) window.location.assign(response.url);
          else throw new Error('Phản hồi không hợp lệ');
          return;
        }
        results.innerHTML = nextResults.innerHTML;
        form.innerHTML = nextForm.innerHTML;
        history.pushState({}, '', response.url);
        if (focusId) document.getElementById(focusId)?.focus({ preventScroll: true });
      } catch (err) {
        if (err.name !== 'AbortError' && error) error.hidden = false;
      } finally {
        if (pendingRequest === request) {
          pendingRequest = null;
          results.setAttribute('aria-busy', 'false');
          results.classList.remove('invoice-results-loading');
        }
      }
    }

    document.addEventListener('submit', event => {
      if (event.target.id !== 'invoice-filter-form') return;
      event.preventDefault();
      event.stopPropagation();
      const url = new URL(event.target.action);
      for (const [key, value] of new FormData(event.target)) {
        if (value) url.searchParams.set(key, value);
      }
      updateInvoices(url);
    }, true);

    document.addEventListener('click', event => {
      const link = event.target.closest('#invoice-filter-form a, #invoice-results .pagination a');
      if (!link || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
      event.preventDefault();
      event.stopPropagation();
      updateInvoices(link.href);
    }, true);
  })();
