
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('propertyModal');

            if (modalElement) {
                const hasErrors = modalElement.querySelector('.is-invalid');

                if (hasErrors && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            }
        });


        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('property_images');
            const preview = document.getElementById('imagePreview');

            if (!input || !preview) {
                return;
            }

            let objectUrls = [];

            input.addEventListener('change', function () {
                objectUrls.forEach(function (url) {
                    URL.revokeObjectURL(url);
                });

                objectUrls = [];
                preview.replaceChildren();

                const files = Array.from(input.files || []);

                files.forEach(function (file, index) {
                    if (!file.type.startsWith('image/')) {
                        return;
                    }

                    const wrapper = document.createElement('div');
                    wrapper.style.width = '120px';

                    const img = document.createElement('img');
                    const url = URL.createObjectURL(file);

                    objectUrls.push(url);

                    img.src = url;
                    img.alt = 'Ảnh xem trước ' + (index + 1);
                    img.style.width = '120px';
                    img.style.height = '90px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '8px';
                    img.style.border = '1px solid #ddd';

                    wrapper.appendChild(img);

                    if (index === 0) {
                        const badge = document.createElement('div');
                        badge.textContent = 'Ảnh đại diện';
                        badge.style.fontSize = '12px';
                        badge.style.marginTop = '4px';
                        badge.style.color = '#20584f';

                        wrapper.appendChild(badge);
                    }

                    preview.appendChild(wrapper);
                });
            });

            const form = document.getElementById('propertyForm');

            if (form) {
                form.addEventListener('submit', function () {
                    // Để trình duyệt tự gửi file theo form multipart/form-data.
                });
            }
        });

        
let propertyMap = null;
let propertyMarker = null;

window.initPropertyMap = function () {
    const mapElement = document.getElementById('propertyMap');

    if (!mapElement) {
        return;
    }

    const latitudeInput = document.getElementById('propertyLatitude');
    const longitudeInput = document.getElementById('propertyLongitude');
    const statusElement = document.getElementById('mapStatus');

    const defaultPosition = {
        lat: 10.7769,
        lng: 106.7009
    };

    const savedLat = parseFloat(latitudeInput.value);
    const savedLng = parseFloat(longitudeInput.value);

    const initialPosition =
        Number.isFinite(savedLat) &&
        Number.isFinite(savedLng)
            ? { lat: savedLat, lng: savedLng }
            : defaultPosition;

    propertyMap = new google.maps.Map(mapElement, {
        center: initialPosition,
        zoom: 14,
        mapTypeControl: true,
        streetViewControl: false,
        fullscreenControl: true
    });

    propertyMarker = new google.maps.Marker({
        position: initialPosition,
        map: propertyMap,
        draggable: true,
        title: 'Vị trí nhà trọ'
    });

    function updateCoordinates(position) {
        const lat = position.lat();
        const lng = position.lng();

        latitudeInput.value = lat.toFixed(7);
        longitudeInput.value = lng.toFixed(7);

        statusElement.textContent =
            `Đã chọn vị trí: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }

    propertyMap.addListener('click', function (event) {
        propertyMarker.setPosition(event.latLng);
        updateCoordinates(event.latLng);
    });

    propertyMarker.addListener('dragend', function (event) {
        updateCoordinates(event.latLng);
    });

    if (latitudeInput.value && longitudeInput.value) {
        statusElement.textContent = 'Đã tải vị trí đã lưu.';
    }


    const modal = document.getElementById('propertyModal');

    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            google.maps.event.trigger(propertyMap, 'resize');

            const lat = parseFloat(latitudeInput.value);
            const lng = parseFloat(longitudeInput.value);

            if (Number.isFinite(lat) && Number.isFinite(lng)) {
                propertyMap.setCenter({ lat, lng });
            }
        });
    }
};
