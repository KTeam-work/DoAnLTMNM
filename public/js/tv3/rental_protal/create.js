
// Hiển thị xem trước ảnh
const imagesInput = document.getElementById('images');
if (imagesInput) {
    imagesInput.addEventListener('change', function () {
        const preview = document.getElementById('imagePreview');
        const files = Array.from(this.files);

        preview.innerHTML = '';

        if (files.length > 10) {
            alert('Chỉ được chọn tối đa 10 ảnh.');
            this.value = '';
            return;
        }

        files.forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function (event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.alt = 'Ảnh xem trước';
                img.className = 'rental-preview-image';
                preview.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    });
}

// Cập nhật thông tin chi tiết phòng khi chọn
const roomSelect = document.getElementById('room_id');
const detailCard = document.getElementById('roomDetailCard');

function updateRoomDetail() {
    if (!roomSelect || !detailCard) return;

    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    if (selectedOption && selectedOption.value && !selectedOption.disabled) {
        document.getElementById('detailRoomTitle').textContent =
            (selectedOption.dataset.name || 'Phòng') +
            (selectedOption.dataset.code ? ' (' + selectedOption.dataset.code + ')' : '');
        document.getElementById('detailProperty').textContent = selectedOption.dataset.property || '-';
        document.getElementById('detailPrice').textContent = selectedOption.dataset.price || '-';
        document.getElementById('detailDeposit').textContent = selectedOption.dataset.deposit || '-';
        document.getElementById('detailArea').textContent = selectedOption.dataset.area || '-';
        document.getElementById('detailMaxPeople').textContent = selectedOption.dataset.maxPeople || '-';

        detailCard.style.display = 'block';
    } else {
        detailCard.style.display = 'none';
    }
}

if (roomSelect) {
    roomSelect.addEventListener('change', updateRoomDetail);
    // Khởi tạo nếu đã có old('room_id')
    updateRoomDetail();
}

// Lọc phòng theo Nhà trọ
const propertyFilter = document.getElementById('propertyFilter');
if (propertyFilter && roomSelect) {
    propertyFilter.addEventListener('change', function () {
        const selectedPropertyId = this.value;
        const optgroups = roomSelect.querySelectorAll('optgroup');

        optgroups.forEach(group => {
            let visibleInGroup = 0;
            const options = group.querySelectorAll('option');

            options.forEach(opt => {
                const match = !selectedPropertyId || opt.dataset.propertyId === selectedPropertyId;
                opt.hidden = !match;
                if (match) visibleInGroup++;
            });

            group.hidden = (visibleInGroup === 0);
        });

        // Nếu option đang chọn bị ẩn đi thì reset về option đầu
        const currentSelected = roomSelect.options[roomSelect.selectedIndex];
        if (currentSelected && currentSelected.hidden) {
            roomSelect.value = '';
        }

        updateRoomDetail();
    });
}
