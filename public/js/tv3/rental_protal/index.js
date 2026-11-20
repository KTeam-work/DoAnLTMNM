

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('postSearch');
    const statusSelect = document.getElementById('postStatus');
    const cards = Array.from(document.querySelectorAll('.post-card'));
    const resultsCount = document.getElementById('resultsCount');

    function filterPosts() {
        const keyword = searchInput.value.trim().toLocaleLowerCase('vi');
        const selectedStatus = statusSelect.value;
        let visibleCount = 0;

        cards.forEach(function (card) {
            const searchText = (card.dataset.search || '').toLocaleLowerCase('vi');
            const cardStatus = card.dataset.status || '';

            const matchesKeyword = searchText.includes(keyword);
            const matchesStatus = !selectedStatus || cardStatus === selectedStatus;
            const visible = matchesKeyword && matchesStatus;

            card.style.display = visible ? '' : 'none';

            if (visible) visibleCount++;
        });

        if (resultsCount) {
            resultsCount.textContent = 'Hiển thị ' + visibleCount + ' / ' + cards.length + ' tin đăng';
        }
    }

    searchInput.addEventListener('input', filterPosts);
    statusSelect.addEventListener('change', function () {
        const val = this.value;
        document.querySelectorAll('.segmented-tab').forEach(function (t) {
            t.classList.toggle('active', (t.dataset.filter || '') === val);
        });
        filterPosts();
    });

    document.querySelectorAll('.segmented-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.segmented-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const filterVal = this.dataset.filter || '';
            if (statusSelect) {
                statusSelect.value = filterVal;
            }
            filterPosts();
        });
    });
});
