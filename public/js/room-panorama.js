(function () {
    const container = document.getElementById('room-panorama-viewer');
    if (!container || container.dataset.initialized) return;
    container.dataset.initialized = 'true';
    const error = document.getElementById('panorama-error');
    const start = () => {
        if (!container.isConnected) return;
        try {
            const viewer = window.pannellum.viewer(container, {
                type: 'equirectangular', panorama: container.dataset.panoramaUrl,
                autoLoad: true, showControls: true, compass: false,
                strings: {loadButtonLabel: 'Mở ảnh 360°', loadingLabel: 'Đang tải ảnh...', bylineLabel: '', noPanoramaError: 'Không thể tải ảnh 360°.'}
            });
            viewer.on('error', () => { if (error) error.hidden = false; });
        } catch (_) { if (error) error.hidden = false; }
    };
    if (window.pannellum) return start();
    const library = document.createElement('script');
    library.src = container.dataset.libraryUrl;
    library.onload = start;
    library.onerror = () => { if (error) error.hidden = false; };
    document.head.appendChild(library);
})();
