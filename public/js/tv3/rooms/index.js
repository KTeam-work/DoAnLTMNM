
        document.addEventListener("DOMContentLoaded", () => {
            updateStatistics();
            initFilters();
        });

        // 1. CẬP NHẬT THỐNG KÊ (STATS)
        function updateStatistics() {
            const cards = document.querySelectorAll('.room-card');
            let total = cards.length;
            let available = 0;
            let rented = 0;
            let maintenance = 0;

            cards.forEach(card => {
                const status = card.dataset.status;
                if (status === 'available') available++;
                else if (status === 'rented') rented++;
                else if (status === 'maintenance') maintenance++;
            });

            document.getElementById('totalRooms').innerText = total < 10 ? '0' + total : total;
            document.getElementById('availableRooms').innerText = available < 10 ? '0' + available : available;
            document.getElementById('rentedRooms').innerText = rented < 10 ? '0' + rented : rented;
            document.getElementById('maintenanceRooms').innerText = maintenance < 10 ? '0' + maintenance : maintenance;
        }

        // 2. LOGIC TÌM KIẾM VÀ LỌC
        function initFilters() {
            const searchInput = document.getElementById('roomSearch');
            const propertySelect = document.getElementById('propertyFilter');
            const statusSelect = document.getElementById('statusFilter');

            function filterRooms() {
                const searchVal = searchInput.value.toLowerCase().trim();
                const propertyVal = propertySelect.value;
                const statusVal = statusSelect.value;
                const cards = document.querySelectorAll('.room-card');
                let visibleCount = 0;

                cards.forEach(card => {
                    const searchData = (card.dataset.search || '').toLowerCase();
                    const propertyData = card.dataset.property;
                    const statusData = card.dataset.status;

                    const matchSearch = !searchVal || searchData.includes(searchVal);
                    const matchProperty = !propertyVal || propertyData === propertyVal;
                    const matchStatus = !statusVal || statusData === statusVal;

                    if (matchSearch && matchProperty && matchStatus) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Xử lý thông báo khi tìm kiếm không ra kết quả
                let emptyMsg = document.getElementById('emptyRoomMsg');
                if (visibleCount === 0) {
                    if (!emptyMsg) {
                        emptyMsg = document.createElement('div');
                        emptyMsg.id = 'emptyRoomMsg';
                        emptyMsg.className = 'empty-room-message';
                        emptyMsg.innerHTML = '<div class="empty-room-icon">🔍</div>Không tìm thấy phòng nào phù hợp với bộ lọc.';
                        document.getElementById('roomGrid').appendChild(emptyMsg);
                    }
                } else if (emptyMsg) {
                    emptyMsg.remove();
                }
            }

            searchInput.addEventListener('input', filterRooms);
            propertySelect.addEventListener('change', filterRooms);
            statusSelect.addEventListener('change', filterRooms);
        }

        // 3. XỬ LÝ TOAST
        function showToast(text) {
            const toast = document.getElementById('toastMessage');
            toast.innerText = text;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // 4. BẬT/TẮT MODAL
        function openModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        // 5. MỞ MODAL CHI TIẾT PHÒNG
        function openDetailModal(btn) {
            const card = btn.closest('.room-card');
            const code = card.querySelector('.room-code').innerText;
            const name = card.querySelector('h3').innerText;
            const prop = card.querySelector('.room-property').innerText;
            const price = card.querySelector('.room-price').innerText;
            const img = card.querySelector('.room-img img').src;
            const statusText = card.querySelector('.room-status').innerText;
            const statusClass = card.dataset.status;

            // Điền dữ liệu
            document.getElementById('detailTitle').innerText = name;
            document.getElementById('detailSubtitle').innerText = "Mã phòng: " + code;
            document.getElementById('detailProperty').innerText = prop;
            document.getElementById('detailPrice').innerText = price;
            document.getElementById('detailImg').src = img;

            const infoSpans = card.querySelectorAll('.room-info span');
            let specs = [];
            infoSpans.forEach(s => specs.push(s.innerText));
            document.getElementById('detailSpecs').innerText = specs.join(' • ');

            const statusEl = document.getElementById('detailStatus');
            statusEl.innerText = statusText;
            statusEl.className = 'detail-status ' + statusClass;

            const amenities = card.querySelectorAll('.amenity');
            const amenitiesContainer = document.getElementById('detailAmenities');
            amenitiesContainer.innerHTML = '';
            amenities.forEach(a => {
                const span = document.createElement('span');
                span.className = 'detail-amenity';
                span.innerText = a.innerText;
                amenitiesContainer.appendChild(span);
            });

            openModal('detailModal');
        }

        // 6. MỞ & LƯU EDIT MODAL
        function openEditModal(btn) {
            const card = btn.closest('.room-card');
            document.getElementById('editCardId').value = card.dataset.roomId;

            const name = card.querySelector('h3').innerText;
            const code = card.querySelector('.room-code').innerText;
            const priceText = card.querySelector('.room-price').innerText.replace(/\D/g, '');

            document.getElementById('editRoomName').value = name;
            document.getElementById('editRoomCode').value = code;
            document.getElementById('editProperty').value = card.dataset.property;
            document.getElementById('editStatus').value = card.dataset.status;
            document.getElementById('editPrice').value = priceText;

            // Checkbox tiện ích
            const currentAmenities = Array.from(card.querySelectorAll('.amenity')).map(a => a.innerText.trim());
            const checkboxes = document.querySelectorAll('input[name="editAmenities"]');
            checkboxes.forEach(cb => {
                cb.checked = currentAmenities.includes(cb.value);
            });

            openModal('editModal');
        }

        function saveEditRoom(e) {
            e.preventDefault();
            const roomId = document.getElementById('editCardId').value;
            const card = document.querySelector(`.room-card[data-room-id="${roomId}"]`);

            if (card) {
                const name = document.getElementById('editRoomName').value;
                const code = document.getElementById('editRoomCode').value;
                const property = document.getElementById('editProperty').value;
                const status = document.getElementById('editStatus').value;
                const price = parseInt(document.getElementById('editPrice').value).toLocaleString('vi-VN') + 'đ / tháng';

                // Cập nhật DOM
                card.dataset.property = property;
                card.dataset.status = status;
                card.dataset.search = `${name} ${code} ${property}`.toLowerCase();

                card.querySelector('h3').innerText = name;
                card.querySelector('.room-code').innerText = code;
                card.querySelector('.room-property').innerText = "Nhà trọ " + property;
                card.querySelector('.room-price').innerText = price;

                const statusTag = card.querySelector('.room-status');
                statusTag.className = 'room-status ' + status;
                statusTag.innerText = status === 'available' ? 'CÒN TRỐNG' : status === 'rented' ? 'ĐANG THUÊ' : 'BẢO TRÌ';

                // Tiện ích
                const amenitiesContainer = card.querySelector('.amenities');
                amenitiesContainer.innerHTML = '';
                const checkedBoxes = document.querySelectorAll('input[name="editAmenities"]:checked');
                checkedBoxes.forEach(cb => {
                    const span = document.createElement('span');
                    span.className = 'amenity';
                    span.innerText = cb.value;
                    amenitiesContainer.appendChild(span);
                });

                updateStatistics();
                closeModal('editModal');
                showToast('Đã lưu thông tin phòng thành công!');
            }
        }

        // 7. MỞ & LƯU ADD MODAL
        function openAddModal() {
            document.getElementById('addRoomForm').reset();
            openModal('addModal');
        }

        function saveAddRoom(e) {
            e.preventDefault();
            const name = document.getElementById('addRoomName').value;
            const code = document.getElementById('addRoomCode').value;
            const property = document.getElementById('addProperty').value;
            const status = document.getElementById('addStatus').value;
            const priceNum = parseInt(document.getElementById('addPrice').value) || 0;
            const price = priceNum.toLocaleString('vi-VN') + 'đ / tháng';
            const area = document.getElementById('addArea').value || '20';
            const capacity = document.getElementById('addCapacity').value || '2';
            const wc = document.getElementById('addWc').value || '1';

            const roomId = Date.now().toString(); // Tạo ID tạm thời
            const statusText = status === 'available' ? 'CÒN TRỐNG' : status === 'rented' ? 'ĐANG THUÊ' : 'BẢO TRÌ';

            // Lấy danh sách tiện ích được chọn
            const checkedBoxes = document.querySelectorAll('input[name="addAmenities"]:checked');
            let amenitiesHTML = '';
            checkedBoxes.forEach(cb => {
                amenitiesHTML += `<span class="amenity">${cb.value}</span>`;
            });

            const newCardHTML = `
            <article class="room-card" data-room-id="${roomId}" data-property="${property}" data-status="${status}" data-search="${name.toLowerCase()} ${code.toLowerCase()} ${property.toLowerCase()}">
                <div class="room-img">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80" alt="${name}">
                    <span class="room-code">${code}</span>
                    <span class="room-status ${status}">${statusText}</span>
                </div>
                <div class="room-body">
                    <h3>${name}</h3>
                    <div class="room-property">Nhà trọ ${property}</div>
                    <div class="room-price">${price}</div>
                    <div class="room-info">
                        <span>${area} m²</span>
                        <span>${capacity} người</span>
                        <span>${wc} WC</span>
                    </div>
                    <div class="amenities">
                        ${amenitiesHTML}
                    </div>
                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">Chỉnh sửa</button>
                        <button class="btn-more" onclick="openDetailModal(this)">Chi tiết</button>
                    </div>
                </div>
            </article>
        `;

            document.getElementById('roomGrid').insertAdjacentHTML('afterbegin', newCardHTML);
            updateStatistics();
            closeModal('addModal');
            showToast('Đã thêm phòng mới thành công!');
        }
