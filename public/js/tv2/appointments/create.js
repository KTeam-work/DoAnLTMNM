 document.getElementById('room_id')?.addEventListener('change', function () {
            if (this.value) {
                const url = new URL(window.location.href);
                url.searchParams.set('id', this.value);
                window.location.href = url.toString();
            }
        });