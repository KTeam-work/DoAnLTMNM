(function () {
    function normalizeText(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/\u0111/g, 'd')
            .replace(/đ/g, 'd')
            .trim();
    }

    function initRoomManage() {
        const searchInput = document.getElementById('roomSearch');
        const propertyFilter = document.getElementById('propertyFilter');
        const statusFilter = document.getElementById('statusFilter');
        const tabBtns = document.querySelectorAll('.segmented-tab');
        const noResults = document.getElementById('noSearchResults');
        const resultCount = document.getElementById('resultCount');

        if (!searchInput && !propertyFilter && !statusFilter && tabBtns.length === 0) {
            return;
        }

        function filterRooms() {
            const keyword = normalizeText(searchInput ? searchInput.value : '');
            const status = statusFilter ? statusFilter.value : '';
            const property = propertyFilter ? propertyFilter.value : '';
            const rows = Array.from(document.querySelectorAll('.room-row'));
            let visibleCount = 0;

            rows.forEach(function (row) {
                const searchText = normalizeText(row.dataset.search || row.textContent);
                const rowStatus = row.dataset.status || '';
                const rowProperty = row.dataset.property || '';

                const matchesKeyword = !keyword || searchText.includes(keyword);
                const matchesStatus = !status || rowStatus === status;
                const matchesProperty = !property || String(rowProperty) === String(property);
                const visible = matchesKeyword && matchesStatus && matchesProperty;

                row.style.display = visible ? '' : 'none';

                if (visible) {
                    visibleCount++;
                }
            });

            if (noResults) {
                noResults.style.display = (rows.length > 0 && visibleCount === 0) ? '' : 'none';
            }

            if (resultCount) {
                resultCount.textContent = 'Đang hiển thị ' + visibleCount + '/' + rows.length + ' phòng';
            }
        }

        if (searchInput) {
            searchInput.oninput = filterRooms;
        }

        if (propertyFilter) {
            propertyFilter.onchange = filterRooms;
        }

        if (statusFilter) {
            statusFilter.onchange = function () {
                const val = this.value;
                tabBtns.forEach(btn => {
                    btn.classList.toggle('active', (btn.dataset.status || '') === val);
                });
                filterRooms();
            };
        }

        tabBtns.forEach(btn => {
            btn.onclick = function () {
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const val = this.dataset.status || '';
                if (statusFilter) {
                    statusFilter.value = val;
                }
                filterRooms();
            };
        });

        // Kích hoạt lọc lần đầu
        filterRooms();
    }

    window.initRoomManage = initRoomManage;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRoomManage);
    } else {
        initRoomManage();
    }
})();
