// ========================================================
// QUẢN LÝ NHÀ TRỌ - TRỌ ƠI (INDEX.JS)
// ========================================================

document.addEventListener('DOMContentLoaded', function () {
    // 1. TỰ ĐỘNG MỞ LẠI MODAL NẾU CÓ LỖI VALIDATION
    const modalElement = document.getElementById('propertyModal');
    if (modalElement) {
        const hasErrors = modalElement.querySelector('.is-invalid');
        if (hasErrors && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        }
    }

    // 2. CHỌN VÀ GOM NHIỀU ẢNH (MULTI-IMAGE ACCUMULATOR)
    const fileInput = document.getElementById('property_images');
    const dropzone = document.getElementById('dropzoneBox');
    const preview = document.getElementById('imagePreview');
    const countBadge = document.getElementById('imageCountBadge');
    const propertyForm = document.getElementById('propertyForm');
    const dt = new DataTransfer();

    // Dùng input tạm để mở hộp thoại chọn file mà không làm mất fileInput.files thật
    const tempPicker = document.createElement('input');
    tempPicker.type = 'file';
    tempPicker.multiple = true;
    tempPicker.accept = 'image/jpeg,image/png,image/webp';
    tempPicker.style.display = 'none';
    document.body.appendChild(tempPicker);

    if (dropzone && fileInput && preview) {
        // Bấm vào vùng dropzone để chọn ảnh
        dropzone.addEventListener('click', function () {
            tempPicker.click();
        });

        // Hiệu ứng kéo thả
        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzone.style.borderColor = '#0e5b42';
            dropzone.style.background = '#f0fdf4';
        });

        dropzone.addEventListener('dragleave', function (e) {
            e.preventDefault();
            dropzone.style.borderColor = '#cbd5e1';
            dropzone.style.background = '#f8fafc';
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.style.borderColor = '#cbd5e1';
            dropzone.style.background = '#f8fafc';
            if (e.dataTransfer && e.dataTransfer.files) {
                addFiles(e.dataTransfer.files);
            }
        });

        // Chọn file qua cửa sổ duyệt file (tempPicker)
        tempPicker.addEventListener('change', function () {
            if (tempPicker.files && tempPicker.files.length > 0) {
                addFiles(tempPicker.files);
            }
            tempPicker.value = ''; // Reset input tạm để có thể chọn lại file vừa chọn nếu muốn
        });

        // Nếu fileInput trực tiếp thay đổi
        fileInput.addEventListener('change', function () {
            if (fileInput.files && fileInput.files.length > 0 && fileInput.files !== dt.files) {
                addFiles(fileInput.files);
            }
        });

        // Khi submit form, luôn đảm bảo fileInput.files nhận đủ toàn bộ dt.files
        if (propertyForm) {
            propertyForm.addEventListener('submit', function () {
                fileInput.files = dt.files;
            });
        }

        function addFiles(files) {
            const maxImages = 10;
            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                if (dt.items.length >= maxImages) return;

                // Tránh thêm file trùng tên và dung lượng
                const isDuplicate = Array.from(dt.files).some(
                    f => f.name === file.name && f.size === file.size
                );
                if (!isDuplicate) {
                    dt.items.add(file);
                }
            });

            fileInput.files = dt.files;
            renderPreviews();
        }

        function removeFile(indexToRemove) {
            const newDt = new DataTransfer();
            Array.from(dt.files).forEach((file, idx) => {
                if (idx !== indexToRemove) {
                    newDt.items.add(file);
                }
            });

            while (dt.items.length > 0) {
                dt.items.remove(0);
            }
            Array.from(newDt.files).forEach(f => dt.items.add(f));

            fileInput.files = dt.files;
            renderPreviews();
        }

        function renderPreviews() {
            preview.innerHTML = '';
            const files = Array.from(dt.files);

            if (countBadge) {
                countBadge.textContent = `${files.length}/10 ảnh`;
                countBadge.className = files.length > 0 ? 'badge bg-success text-white' : 'badge bg-light text-dark border';
            }

            files.forEach((file, index) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'position-relative border rounded-3 p-1 bg-white shadow-sm';
                wrapper.style.width = '120px';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = file.name;
                img.style.width = '100%';
                img.style.height = '85px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '6px';

                // Nút xóa ảnh
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.innerHTML = '&times;';
                deleteBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center p-0 shadow';
                deleteBtn.style.width = '22px';
                deleteBtn.style.height = '22px';
                deleteBtn.style.fontSize = '14px';
                deleteBtn.style.transform = 'translate(25%, -25%)';
                deleteBtn.title = 'Xóa ảnh này';
                deleteBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    removeFile(index);
                });

                // Nhãn ảnh đại diện / số thứ tự
                const badge = document.createElement('div');
                badge.className = 'text-center mt-1';
                badge.style.fontSize = '11px';
                if (index === 0) {
                    badge.innerHTML = '<span class="text-success fw-bold">★ Ảnh chính</span>';
                } else {
                    badge.innerHTML = `<span class="text-muted">Ảnh ${index + 1}</span>`;
                }

                wrapper.appendChild(img);
                wrapper.appendChild(deleteBtn);
                wrapper.appendChild(badge);
                preview.appendChild(wrapper);
            });

            // Nút thêm tiếp nếu chưa đủ 10 ảnh
            if (files.length > 0 && files.length < 10) {
                const addMoreBox = document.createElement('div');
                addMoreBox.className = 'border border-2 border-dashed rounded-3 d-flex flex-column align-items-center justify-content-center text-muted';
                addMoreBox.style.width = '120px';
                addMoreBox.style.height = '115px';
                addMoreBox.style.cursor = 'pointer';
                addMoreBox.style.background = '#f8fafc';
                addMoreBox.innerHTML = '<span style="font-size: 24px; line-height: 1;">＋</span><span style="font-size: 11.5px; font-weight: 600; margin-top: 4px;">Thêm ảnh</span>';
                addMoreBox.addEventListener('click', () => tempPicker.click());
                preview.appendChild(addMoreBox);
            }
        }
    }

    // 3. BẢN ĐỒ OPENSTREETMAP (LEAFLET - MIỄN PHÍ 100%, KHÔNG CẦN API KEY)
    let propertyMap = null;
    let propertyMarker = null;

    function initLeafletMap() {
        const mapElement = document.getElementById('propertyMap');
        if (!mapElement || typeof L === 'undefined') return;

        if (propertyMap) {
            propertyMap.invalidateSize();
            return;
        }

        const latInput = document.getElementById('propertyLatitude');
        const lngInput = document.getElementById('propertyLongitude');
        const statusEl = document.getElementById('mapStatus');

        // Tọa độ mặc định: TP. Hồ Chí Minh
        let defaultLat = 10.776889;
        let defaultLng = 106.700806;
        let defaultZoom = 13;

        const savedLat = parseFloat(latInput?.value);
        const savedLng = parseFloat(lngInput?.value);

        if (Number.isFinite(savedLat) && Number.isFinite(savedLng) &&
            savedLat >= -90 && savedLat <= 90 && savedLng >= -180 && savedLng <= 180) {
            defaultLat = savedLat;
            defaultLng = savedLng;
            defaultZoom = 16;
        }

        // Tạo bản đồ Leaflet OpenStreetMap
        propertyMap = L.map('propertyMap').setView([defaultLat, defaultLng], defaultZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
        }).addTo(propertyMap);

        // Tạo marker có thể kéo thả
        propertyMarker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(propertyMap);

        function updateInputs(lat, lng) {
            if (latInput) latInput.value = lat.toFixed(7);
            if (lngInput) lngInput.value = lng.toFixed(7);
            if (statusEl) {
                statusEl.innerHTML = `📍 Đã chọn vị trí: <strong>${lat.toFixed(6)}, ${lng.toFixed(6)}</strong>`;
                statusEl.className = 'form-text mt-2 text-success fw-medium';
            }
        }

        // Click vào bản đồ để chọn tọa độ
        propertyMap.on('click', function (e) {
            propertyMarker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        // Kéo thả marker
        propertyMarker.on('dragend', function (e) {
            const pos = propertyMarker.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });

        // KHI NGƯỜI DÙNG TỰ GÕ KINH ĐỘ / VĨ ĐỘ -> BẢN ĐỒ TỰ ĐỘNG NHẢY ĐẾN VỊ TRÍ ĐÓ
        function syncMapFromInputs() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (Number.isFinite(lat) && Number.isFinite(lng) &&
                lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                propertyMarker.setLatLng([lat, lng]);
                propertyMap.setView([lat, lng], 16);
                if (statusEl) {
                    statusEl.innerHTML = `📍 Đã định vị theo tọa độ: <strong>${lat.toFixed(6)}, ${lng.toFixed(6)}</strong>`;
                    statusEl.className = 'form-text mt-2 text-primary fw-medium';
                }
            }
        }

        if (latInput) latInput.addEventListener('input', syncMapFromInputs);
        if (lngInput) lngInput.addEventListener('input', syncMapFromInputs);

        if (Number.isFinite(savedLat) && Number.isFinite(savedLng) && statusEl) {
            statusEl.innerHTML = `📍 Tọa độ hiện tại: <strong>${savedLat.toFixed(6)}, ${savedLng.toFixed(6)}</strong>`;
        }
    }

    // Khi mở Modal lên -> Tự động khởi tạo và resize bản đồ để hiển thị đầy đủ
    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function () {
            if (!propertyMap) {
                initLeafletMap();
            } else {
                propertyMap.invalidateSize();
                const latInput = document.getElementById('propertyLatitude');
                const lngInput = document.getElementById('propertyLongitude');
                const lat = parseFloat(latInput?.value);
                const lng = parseFloat(lngInput?.value);
                if (Number.isFinite(lat) && Number.isFinite(lng) && propertyMarker) {
                    propertyMarker.setLatLng([lat, lng]);
                    propertyMap.setView([lat, lng], 16);
                }
            }
        });
    }
});
