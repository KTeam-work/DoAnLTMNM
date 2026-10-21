
            document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('roomSearch');
            const statusFilter = document.getElementById('statusFilter');
            const rows = Array.from(document.querySelectorAll('.room-row'));
            const noResults = document.getElementById('noSearchResults');
            const resultCount = document.getElementById('resultCount');

            function normalizeText(value) {
                return String(value || '')
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd')
                    .trim();
            }

            function filterRooms() {
                const keyword = normalizeText(searchInput.value);
                const status = statusFilter.value;
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const searchText = normalizeText(row.dataset.search);
                    const rowStatus = row.dataset.status || '';

                    const matchesKeyword = searchText.includes(keyword);
                    const matchesStatus = !status || rowStatus === status;
                    const visible = matchesKeyword && matchesStatus;

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
            statusFilter.addEventListener('change', filterRooms);
        });

