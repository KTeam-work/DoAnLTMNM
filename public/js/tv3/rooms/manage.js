
            document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('roomSearch');
            const propertyFilter = document.getElementById('propertyFilter');
            const statusFilter = document.getElementById('statusFilter');
            const tabBtns = document.querySelectorAll('.segmented-tab');
            const rows = Array.from(document.querySelectorAll('.room-row'));
            const noResults = document.getElementById('noSearchResults');
            const resultCount = document.getElementById('resultCount');

            function normalizeText(value) {
                return String(value || '')
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/\u0111/g, 'd')
                    .replace(/đ/g, 'd')
                    .trim();
            }

            function filterRooms() {
                const keyword = normalizeText(searchInput.value);
                const status = statusFilter.value;
                const property = propertyFilter?.value || '';
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const searchText = normalizeText(row.dataset.search);
                    const rowStatus = row.dataset.status || '';
                    const rowProperty = row.dataset.property || '';

                    const matchesKeyword = searchText.includes(keyword);
                    const matchesStatus = !status || rowStatus === status;
                    const matchesProperty = !property || rowProperty === property;
                    const visible = matchesKeyword && matchesStatus && matchesProperty;

                    row.style.display = visible ? '' : 'none';

                    if (visible) {
                        visibleCount++;
                    }
                });

                if (noResults) {
                    noResults.style.display =
                        rows.length > 0 && visibleCount === 0 ? '' : 'none';
                }

                if (resultCount) {
                    resultCount.textContent =
                        'Đang hiển thị ' + visibleCount + '/' + rows.length + ' phòng';
                }
            }

            searchInput.addEventListener('input', filterRooms);
            propertyFilter?.addEventListener('change', filterRooms);
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const val = this.value;
                    tabBtns.forEach(btn => {
                        btn.classList.toggle('active', (btn.dataset.status || '') === val);
                    });
                    filterRooms();
                });
            }

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const val = this.dataset.status || '';
                    if (statusFilter) {
                        statusFilter.value = val;
                    }
                    filterRooms();
                });
            });
        });

