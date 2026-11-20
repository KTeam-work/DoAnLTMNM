
            document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('images');
            const preview = document.getElementById('newImagePreview');
            const form = document.getElementById('editPropertyForm');

            // Xem trước ảnh mới được chọn
            if (imageInput && preview) {
                imageInput.addEventListener('change', function () {
                    preview.innerHTML = '';

                    const files = Array.from(this.files || []);

                    if (files.length === 0) {
                        preview.style.display = 'none';
                        return;
                    }

                    preview.style.display = 'grid';

                    files.forEach(function (file) {
                        if (!file.type.startsWith('image/')) {
                            return;
                        }

                        const item = document.createElement('div');
                        item.className = 'property-image-item';

                        const img = document.createElement('img');
                        img.alt = file.name;
                        img.src = URL.createObjectURL(file);

                        const name = document.createElement('div');
                        name.className = 'image-help';
                        name.textContent = file.name;

                        item.appendChild(img);
                        item.appendChild(name);
                        preview.appendChild(item);
                    });
                });
            }

            // Làm mờ ảnh được đánh dấu xóa
            document.querySelectorAll('.delete-image-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const item = this.closest('.property-image-item');

                    if (item) {
                        item.classList.toggle('is-deleted', this.checked);
                    }

                    // Không cho chọn ảnh đã đánh dấu xóa làm ảnh đại diện
                    if (this.checked) {
                        const radio = item?.querySelector('.thumbnail-radio');

                        if (radio) {
                            radio.checked = false;
                        }
                    }
                });

                if (checkbox.checked) {
                    checkbox.dispatchEvent(new Event('change'));
                }
            });

            // Khi chọn ảnh đại diện, bỏ chọn xóa ảnh đó
            document.querySelectorAll('.thumbnail-radio').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (!this.checked) {
                        return;
                    }

                    const item = this.closest('.property-image-item');
                    const deleteCheckbox = item?.querySelector('.delete-image-checkbox');

                    if (deleteCheckbox) {
                        deleteCheckbox.checked = false;
                        item.classList.remove('is-deleted');
                    }
                });
            });

            // Cảnh báo trước khi lưu thay đổi ảnh
            if (form) {
                form.addEventListener('submit', function (event) {
                    const selectedFiles = imageInput ? imageInput.files.length : 0;
                    const deletedImages = document.querySelectorAll(
                        '.delete-image-checkbox:checked'
                    ).length;

                    if (selectedFiles > 10) {
                        event.preventDefault();
                        alert('Mày chỉ có thể thêm tối đa 10 ảnh trong một lần.');
                        return;
                    }

                    if (deletedImages > 0) {
                        const confirmed = confirm(
                            'Bạn đã đánh dấu ' + deletedImages +
                            ' ảnh cần xóa. Tiếp tục lưu thay đổi nhé?'
                        );

                        if (!confirmed) {
                            event.preventDefault();
                        }
                    }
                });
            }

            // Khởi tạo bản đồ OpenStreetMap (Leaflet)
            const mapElement = document.getElementById('propertyMap');
            if (mapElement && typeof L !== 'undefined') {
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                const statusEl = document.getElementById('mapStatus');

                let defaultLat = 10.776889;
                let defaultLng = 106.700806;
                let defaultZoom = 13;

                const savedLat = parseFloat(latInput ? latInput.value : '');
                const savedLng = parseFloat(lngInput ? lngInput.value : '');

                if (Number.isFinite(savedLat) && Number.isFinite(savedLng) &&
                    savedLat >= -90 && savedLat <= 90 && savedLng >= -180 && savedLng <= 180) {
                    defaultLat = savedLat;
                    defaultLng = savedLng;
                    defaultZoom = 16;
                }

                const map = L.map('propertyMap').setView([defaultLat, defaultLng], defaultZoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
                }).addTo(map);

                const marker = L.marker([defaultLat, defaultLng], {
                    draggable: true
                }).addTo(map);

                function updateCoords(lat, lng) {
                    if (latInput) latInput.value = lat.toFixed(7);
                    if (lngInput) lngInput.value = lng.toFixed(7);
                    if (statusEl) {
                        statusEl.innerHTML = `📍 Đã chọn vị trí: <strong>${lat.toFixed(6)}, ${lng.toFixed(6)}</strong>`;
                        statusEl.className = 'form-text mt-2 text-success fw-medium';
                    }
                }

                map.on('click', function (e) {
                    marker.setLatLng(e.latlng);
                    updateCoords(e.latlng.lat, e.latlng.lng);
                });

                marker.on('dragend', function (e) {
                    const pos = marker.getLatLng();
                    updateCoords(pos.lat, pos.lng);
                });

                function syncMapFromInputs() {
                    const lat = parseFloat(latInput.value);
                    const lng = parseFloat(lngInput.value);
                    if (Number.isFinite(lat) && Number.isFinite(lng) &&
                        lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                        marker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 16);
                        if (statusEl) {
                            statusEl.innerHTML = `📍 Đã định vị theo tọa độ: <strong>${lat.toFixed(6)}, ${lng.toFixed(6)}</strong>`;
                            statusEl.className = 'form-text mt-2 text-primary fw-medium';
                        }
                    }
                }

                if (latInput) latInput.addEventListener('input', syncMapFromInputs);
                if (lngInput) lngInput.addEventListener('input', syncMapFromInputs);

                setTimeout(() => map.invalidateSize(), 300);
            }
        });
