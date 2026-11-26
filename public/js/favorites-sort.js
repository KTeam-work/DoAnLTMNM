(() => {
    if (window.favoriteSortInitialized) return;
    window.favoriteSortInitialized = true;

    function sortFavorites(mode) {
        const grid = document.getElementById('favorites-grid');
        if (!grid) return;
        const cards = Array.from(grid.querySelectorAll('.favorite-card-column'));
        cards.sort((a, b) => {
            if (mode === 'price-asc') return Number(a.dataset.price) - Number(b.dataset.price);
            if (mode === 'price-desc') return Number(b.dataset.price) - Number(a.dataset.price);
            if (mode === 'area-desc') return Number(b.dataset.area) - Number(a.dataset.area);
            return Number(a.dataset.order) - Number(b.dataset.order);
        });
        cards.forEach(card => grid.appendChild(card));
    }

    document.addEventListener('click', event => {
        const button = event.target.closest('[data-favorite-sort]');
        if (!button) return;
        sortFavorites(button.dataset.favoriteSort);
        document.querySelectorAll('[data-favorite-sort]').forEach(item => {
            const active = item === button;
            item.classList.toggle('active', active);
            item.setAttribute('aria-pressed', String(active));
        });
        const select = document.getElementById('favorites-sort');
        if (select) select.value = button.dataset.favoriteSort;
    });

    document.addEventListener('change', event => {
        if (event.target.id !== 'favorites-sort') return;
        sortFavorites(event.target.value);
        document.querySelectorAll('[data-favorite-sort]').forEach(item => {
            item.classList.remove('active');
            item.setAttribute('aria-pressed', 'false');
        });
    });
})();
