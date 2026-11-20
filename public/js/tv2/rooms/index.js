
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('room-search-form');
    const results = document.getElementById('room-results');
    const count = document.getElementById('room-result-count');
    const error = document.getElementById('room-search-error');

    if (!form || !results || !count) return;

    let currentRequest = null;

    async function searchRooms(url = null, updateHistory = true) {
        if (currentRequest) {
            currentRequest.abort();
        }

        currentRequest = new AbortController();

        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (String(value).trim() !== '') {
                params.append(key, value);
            }
        }

        const requestUrl = url
            ? new URL(url, window.location.origin)
            : new URL(form.action, window.location.origin);

        if (!url) {
            requestUrl.search = params.toString();
        }

        results.setAttribute('aria-busy', 'true');
        error?.classList.add('d-none');

        try {
            const response = await fetch(requestUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                signal: currentRequest.signal
            });

            if (!response.ok) {
                throw new Error('Không thể tải danh sách phòng.');
            }

            const data = await response.json();

            results.innerHTML = data.html;
            count.textContent =
                `Tìm thấy ${data.total} phòng phù hợp.`;

            if (updateHistory) {
                window.history.pushState(
                    {},
                    '',
                    requestUrl.pathname + requestUrl.search
                );
            }
        } catch (exception) {
            if (exception.name !== 'AbortError') {
                if (error) error.classList.remove('d-none');
                console.error(exception);
            }
        } finally {
            results.setAttribute('aria-busy', 'false');
            currentRequest = null;
        }
    }

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        searchRooms();
    });

    // Nhấn nhanh vào khoảng giá.
    document.querySelectorAll('[data-min-price][data-max-price]')
        .forEach((button) => {
            button.addEventListener('click', () => {
                form.elements.min_price.value =
                    button.dataset.minPrice === '0'
                        ? ''
                        : button.dataset.minPrice;

                form.elements.max_price.value =
                    button.dataset.maxPrice;

                searchRooms();
            });
        });

    function clearFilters() {
        form.reset();
        searchRooms();
    }

    document.getElementById('clear-room-filters')
        ?.addEventListener('click', clearFilters);

    // Nút xóa bộ lọc trong trạng thái không có kết quả.
    results.addEventListener('click', (event) => {
        if (event.target.closest('#reset-empty-room-filters')) {
            clearFilters();
        }
    });

    // Phân trang cũng tải kết quả bằng AJAX.
    results.addEventListener('click', (event) => {
        const link = event.target.closest('.room-pagination a');

        if (!link) return;

        event.preventDefault();
        searchRooms(link.href);
    });

    // Cho phép nút Back/Forward của trình duyệt hoạt động.
    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);

        form.reset();

        for (const [key, value] of url.searchParams.entries()) {
            const field = form.elements.namedItem(key);

            if (!field) continue;

            if (field instanceof RadioNodeList) {
                continue;
            }

            if (field instanceof HTMLInputElement &&
                field.type === 'checkbox') {
                field.checked = [...form.querySelectorAll(
                    `input[name="${key}"]`
                )].some(input => input.value === value);
            } else {
                field.value = value;
            }
        }

        searchRooms(window.location.href, false);
    });
});
