
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editRoomForm');
    const saveButton = document.getElementById('saveButton');

    const fields = {
        name: document.getElementById('name'),
        area: document.getElementById('area'),
        people: document.getElementById('max_people'),
        price: document.getElementById('price'),
        deposit: document.getElementById('deposit'),
        status: document.getElementById('status'),
        description: document.getElementById('description')
    };

    const initialValues = new FormData(form);
    const initialSnapshot = JSON.stringify(Array.from(initialValues.entries()));
    let submitted = false;

    const statusLabels = {
        available: 'Còn trống',
        pending: 'Đang chờ',
        rented: 'Đang thuê',
        maintenance: 'Bảo trì',
        hidden: 'Đang ẩn'
    };

    function formatMoney(value) {
        const number = Number(value);
        if (value === '' || !Number.isFinite(number)) return '0đ';

        return new Intl.NumberFormat('vi-VN', {
            maximumFractionDigits: 0
        }).format(number) + 'đ';
    }

    function updatePreview() {
        document.getElementById('previewName').textContent =
            fields.name.value.trim() || 'Chưa đặt tên phòng';

        document.getElementById('previewPrice').textContent =
            formatMoney(fields.price.value);

        document.getElementById('previewArea').textContent =
            fields.area.value || '0';

        document.getElementById('previewPeople').textContent =
            fields.people.value || '0';

        document.getElementById('previewStatus').textContent =
            statusLabels[fields.status.value] || fields.status.value;

        document.getElementById('pricePreview').textContent =
            'Hiển thị: ' + formatMoney(fields.price.value);

        document.getElementById('depositPreview').textContent =
            'Hiển thị: ' + formatMoney(fields.deposit.value);

        const statusNote = document.getElementById('statusNote');
        const notes = {
            rented: 'Phòng đang thuê. Hãy kiểm tra hợp đồng hiện tại trước khi đổi trạng thái.',
            maintenance: 'Phòng bảo trì nên không phù hợp để tiếp nhận người thuê mới.',
            hidden: 'Phòng ẩn có thể không xuất hiện trong danh sách công khai.',
            pending: 'Phòng đang chờ xử lý. Hãy kiểm tra trạng thái nghiệp vụ liên quan.'
        };

        statusNote.hidden = !notes[fields.status.value];
        statusNote.textContent = notes[fields.status.value] || '';
    }

    function hasChanges() {
        return JSON.stringify(
            Array.from(new FormData(form).entries())
        ) !== initialSnapshot;
    }

    Object.values(fields).forEach(function (field) {
        field.addEventListener('input', updatePreview);
        field.addEventListener('change', updatePreview);
    });

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            form.classList.add('was-validated');
            form.reportValidity();
            return;
        }

        const area = Number(fields.area.value);
        const price = Number(fields.price.value);
        const deposit = Number(fields.deposit.value);
        const people = Number(fields.people.value);

        if (area <= 0 || price < 0 || deposit < 0 || people < 1) {
            event.preventDefault();
            alert('Vui lòng kiểm tra diện tích, giá thuê, tiền cọc và sức chứa.');
            return;
        }

        submitted = true;
        saveButton.disabled = true;
        saveButton.textContent = 'Đang lưu...';
    });

    window.addEventListener('beforeunload', function (event) {
        if (!submitted && hasChanges()) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    updatePreview();
});
