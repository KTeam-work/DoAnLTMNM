/**
 * Trọ Ơi - Universal Seamless AJAX Navigation & Instant Tab Switching
 * Áp dụng toàn bộ cho: Navbar, Menu, Phân trang và Bộ lọc Tab trên Admin, Chủ trọ (Landlord) và Người thuê (Tenant).
 * Tự động đồng bộ CSS / <head> và Navbar để KHÔNG BAO GIỜ bị mất layout khi bấm qua lại giữa các trang.
 * Không làm mờ màn hình - hiển thị thanh tiến trình tinh tế (Top Loader) ở mép trên cùng.
 */
(function () {
    let isFetching = false;

    // Khởi tạo thanh tiến trình mỏng mép trên màn hình (Top Progress Bar)
    function getOrCreateTopLoader() {
        let loader = document.getElementById('tro-oi-ajax-loader');
        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'tro-oi-ajax-loader';
            loader.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                width: 0%;
                background: linear-gradient(90deg, #10b981, #059669, #38bdf8);
                box-shadow: 0 0 10px rgba(16, 185, 129, 0.7);
                z-index: 9999999;
                transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease;
                pointer-events: none;
                opacity: 0;
            `;
            document.body.appendChild(loader);
        }
        return loader;
    }

    function startTopLoader() {
        const loader = getOrCreateTopLoader();
        loader.style.transition = 'width 0.2s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.15s ease';
        loader.style.opacity = '1';
        loader.style.width = '35%';
        setTimeout(() => {
            if (isFetching) {
                loader.style.width = '75%';
            }
        }, 120);
    }

    function finishTopLoader() {
        const loader = getOrCreateTopLoader();
        loader.style.width = '100%';
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.width = '0%';
            }, 250);
        }, 150);
    }

    // Lấy thẻ <main> chứa nội dung chính của trang
    function getMainElement(doc = document) {
        return doc.querySelector('main');
    }

    // Đồng bộ CSS stylesheets, inline styles và thẻ title từ trang mới
    function syncHead(newDoc) {
        if (!newDoc) return;

        // 1. Cập nhật Title
        if (newDoc.title) {
            document.title = newDoc.title;
        }

        // 2. Đồng bộ các file <link rel="stylesheet">
        const existingLinks = new Set(
            Array.from(document.querySelectorAll('link[rel="stylesheet"]')).map(l => l.href)
        );

        const newLinks = newDoc.querySelectorAll('link[rel="stylesheet"]');
        newLinks.forEach(link => {
            if (link.href && !existingLinks.has(link.href)) {
                const newLink = document.createElement('link');
                newLink.rel = 'stylesheet';
                newLink.href = link.href;
                document.head.appendChild(newLink);
            }
        });

        // 3. Đồng bộ các thẻ <style> nội tuyến nếu có
        const existingStyles = new Set(
            Array.from(document.querySelectorAll('head style')).map(s => s.textContent.trim())
        );

        const newStyles = newDoc.querySelectorAll('head style');
        newStyles.forEach(style => {
            const content = style.textContent.trim();
            if (content && !existingStyles.has(content)) {
                const newStyle = document.createElement('style');
                newStyle.textContent = style.textContent;
                document.head.appendChild(newStyle);
            }
        });
    }

    // Cập nhật trạng thái active trên Navbar
    function syncNavbar(newDoc) {
        const currentNav = document.querySelector('nav.app-navbar');
        const newNav = newDoc.querySelector('nav.app-navbar');
        if (currentNav && newNav) {
            currentNav.innerHTML = newNav.innerHTML;
        }

        // Đóng menu mobile nếu đang mở
        const openCollapse = document.querySelector('.navbar-collapse.show');
        if (openCollapse) {
            openCollapse.classList.remove('show');
        }
    }

    // Kích hoạt lại các script đặc thù của trang mới nếu có
    function reexecuteScripts(container, newDoc) {
        // 1. Chạy các script nằm trong <main>
        const inlineScripts = container.querySelectorAll('script');
        inlineScripts.forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => {
                newScript.setAttribute(attr.name, attr.value);
            });
            newScript.textContent = oldScript.textContent;
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });

        // 2. Nạp và kích hoạt các script từ newDoc.body (bao gồm cả inline scripts trong @push('scripts'))
        if (newDoc) {
            const currentScriptSrcs = new Set(
                Array.from(document.querySelectorAll('script[src]')).map(s => s.src)
            );

            // Nạp external script mới nếu chưa có
            const newPageExternalScripts = newDoc.querySelectorAll('body script[src]');
            newPageExternalScripts.forEach(s => {
                if (s.src && !currentScriptSrcs.has(s.src)) {
                    const tag = document.createElement('script');
                    tag.src = s.src;
                    if (s.defer) tag.defer = true;
                    document.body.appendChild(tag);
                }
            });

            // Chạy inline script bên ngoài <main> (ví dụ từ stack scripts)
            const newBodyInlineScripts = newDoc.querySelectorAll('body > script:not([src])');
            newBodyInlineScripts.forEach(s => {
                const scriptContent = s.textContent.trim();
                if (scriptContent && !scriptContent.includes('tro-oi-ajax-loader')) {
                    try {
                        const runScript = new Function(scriptContent);
                        runScript();
                    } catch (e) {
                        console.warn('Script execution notice:', e);
                    }
                }
            });
        }

        // Kích hoạt lại event DOMContentLoaded cho các script tự lắng nghe
        try {
            document.dispatchEvent(new Event('DOMContentLoaded'));
        } catch (e) {
            // bỏ qua
        }
    }

    // Hàm tải nội dung trang mới qua AJAX
    async function navigateAjax(url, push = true) {
        if (isFetching) return;
        const currentMain = getMainElement();
        if (!currentMain) {
            window.location.href = url;
            return;
        }

        const isSamePage = (new URL(url, window.location.origin).pathname === window.location.pathname);

        try {
            isFetching = true;
            // Bật thanh tải mỏng phía trên cùng, KHÔNG làm mờ toàn bộ màn hình
            startTopLoader();

            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!res.ok) {
                window.location.href = url;
                return;
            }

            const html = await res.text();
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');

            const newMain = getMainElement(newDoc);
            if (!newMain) {
                window.location.href = url;
                return;
            }

            // 1. Đồng bộ <head> (CSS, Title) để không bao giờ bị vỡ layout
            syncHead(newDoc);

            // 2. Đồng bộ thanh Navbar (cập nhật active tab)
            syncNavbar(newDoc);

            // 3. Thay thế nội dung <main>
            currentMain.innerHTML = newMain.innerHTML;

            // 4. Kích hoạt lại các script của trang mới
            reexecuteScripts(currentMain, newDoc);

            // 5. Cập nhật URL trình duyệt
            if (push) {
                window.history.pushState({ ajaxNav: true, url }, '', url);
            }

            // Nếu sang trang mới thì cuộn lên đầu; nếu chỉ lọc trong cùng trang thì giữ vị trí
            if (!isSamePage) {
                window.scrollTo({ top: 0, behavior: 'instant' });
            }
        } catch (err) {
            console.error('AJAX navigation error:', err);
            window.location.href = url;
        } finally {
            finishTopLoader();
            isFetching = false;
        }
    }

    // Xử lý sự kiện CLICK toàn cục
    document.addEventListener('click', function (e) {
        // Tìm thẻ <a> gần nhất
        const link = e.target.closest('a[href]');
        if (!link) return;

        // Bỏ qua các trường hợp không cần AJAX
        if (link.closest('form')) return; // Bỏ qua nếu nằm trong form (VD: form logout)
        if (link.hasAttribute('download')) return;
        if (link.getAttribute('target') === '_blank') return;
        if (link.hasAttribute('data-bs-toggle')) return; // Dropdown toggle hoặc Modal toggle
        if (link.hasAttribute('data-no-ajax')) return;
        if (link.hasAttribute('onclick')) return; // JS click handlers

        const rawHref = link.getAttribute('href') || '';
        if (rawHref === '#' || rawHref.startsWith('javascript:')) return;

        const targetUrl = new URL(link.href, window.location.origin);

        // Chỉ chặn các link nội bộ cùng tên miền
        if (targetUrl.origin === window.location.origin) {
            // Không chặn đường dẫn logout
            if (targetUrl.pathname === '/logout') return;

            // 1. Nếu là tab phân đoạn, highlight giao diện ngay lập tức
            const bar = link.closest('.segmented-filter-bar');
            if (bar) {
                bar.querySelectorAll('.segmented-tab').forEach(t => t.classList.remove('active'));
                link.classList.add('active');
            }

            // 2. Nếu là chip filter, highlight ngay
            const chip = link.closest('.chip-filter');
            if (chip && chip.parentElement) {
                chip.parentElement.querySelectorAll('.chip-filter').forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
            }

            e.preventDefault();
            navigateAjax(link.href, true);
        }
    });

    // Xử lý khi SUBMIT FORM LỌC GET (tìm kiếm, dropdown lọc)
    document.addEventListener('submit', function (e) {
        const form = e.target.closest('form');
        if (!form || form.hasAttribute('data-no-ajax') || form.id === 'chatForm') return;

        const isFilterForm = form.classList.contains('rp-filters') ||
                             form.classList.contains('tv1-filter-form') ||
                             form.hasAttribute('data-ajax-filter');

        if (isFilterForm && form.method.toUpperCase() === 'GET') {
            const formAction = new URL(form.action || window.location.href, window.location.origin);
            if (formAction.origin === window.location.origin) {
                e.preventDefault();
                const formData = new FormData(form);
                const params = new URLSearchParams();
                for (const [key, value] of formData.entries()) {
                    if (value.trim() !== '') {
                        params.append(key, value);
                    }
                }
                const fullUrl = formAction.pathname + (params.toString() ? '?' + params.toString() : '');
                navigateAjax(fullUrl, true);
            }
        }
    });

    // Lắng nghe nút BACK / FORWARD của trình duyệt
    window.addEventListener('popstate', function () {
        navigateAjax(window.location.href, false);
    });
})();
