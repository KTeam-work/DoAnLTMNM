
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editRoomForm');
    const saveButton = document.getElementById('saveButton');

    const fields = {
        name: document.getElementById('name'),
        area: document.getElementById('area'),
        people: document.getElementById('max_people'),
        price: document.getElementById('price'),
        deposit: document.getElementById('deposit'),
        status: document.getElementById('status') || document.getElementById('status_disabled'),
        description: document.getElementById('description')
    };

    const initialValues = new FormData(form);
    const initialSnapshot = JSON.stringify(Array.from(initialValues.entries()));
    let submitted = false;

    const statusLabels = {
        available: 'Còn trống',
        rented: 'Đang thuê',
        maintenance: 'Đang bảo trì',
        hidden: 'Đang ẩn'
    };

    function formatMoney(value) {
        const raw = String(value).replace(/\D/g, '');
        if (!raw) return '0đ';
        const number = Number(raw);
        if (!Number.isFinite(number)) return '0đ';

        return new Intl.NumberFormat('vi-VN', {
            maximumFractionDigits: 0
        }).format(number) + 'đ';
    }

    function formatWords(num) {
        if (!num || num <= 0) return '';
        if (num >= 1e9) {
            const ty = (num / 1e9).toLocaleString('vi-VN', { maximumFractionDigits: 2 });
            return `${ty} tỷ đồng`;
        }
        if (num >= 1e6) {
            const tr = (num / 1e6).toLocaleString('vi-VN', { maximumFractionDigits: 2 });
            return `${tr} triệu đồng`;
        }
        if (num >= 1e3) {
            const k = (num / 1e3).toLocaleString('vi-VN', { maximumFractionDigits: 2 });
            return `${k} nghìn đồng`;
        }
        return `${num.toLocaleString('vi-VN')} đồng`;
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
            fields.status ? (statusLabels[fields.status.value] || fields.status.value) : '';

        // Chuẩn hóa giá thuê và tiền cọc về dạng số
        const rawPrice = String(fields.price.value).replace(/\D/g, '');
        const rawDeposit = String(fields.deposit.value).replace(/\D/g, '');

        const priceNum = rawPrice ? parseInt(rawPrice, 10) : 0;
        const depositNum = rawDeposit ? parseInt(rawDeposit, 10) : 0;

        // Định dạng giá thuê
        if (rawPrice) {
            fields.price.value = priceNum.toLocaleString('vi-VN');
            document.getElementById('pricePreview').textContent =
                '≈ ' + formatWords(priceNum);
        } else {
            document.getElementById('pricePreview').textContent = '';
        }

        // Kiểm tra và định dạng tiền cọc
        if (rawPrice && priceNum > 0) {
            fields.deposit.max = priceNum;
        } else {
            fields.deposit.removeAttribute('max');
        }

        if (rawDeposit) {
            fields.deposit.value = depositNum.toLocaleString('vi-VN');

            if (rawPrice && depositNum > priceNum) {
                fields.deposit.classList.add('is-invalid');

                document.getElementById('depositPreview').textContent =
                    'Tiền cọc không được lớn hơn giá thuê';
            } else {
                fields.deposit.classList.remove('is-invalid');

                document.getElementById('depositPreview').textContent =
                    '≈ ' + formatWords(depositNum);
            }
        } else {
            fields.deposit.classList.remove('is-invalid');
            document.getElementById('depositPreview').textContent = '';
        }

        }

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
        const price = Number(String(fields.price.value).replace(/\D/g, ''));
        const deposit = Number(String(fields.deposit.value).replace(/\D/g, ''));
        const people = Number(fields.people.value);

        if (area <= 0 || price < 0 || deposit < 0 || people < 1) {
            event.preventDefault();
            alert('Vui lòng kiểm tra diện tích, giá thuê, tiền cọc và sức chứa.');
            return;
        }

        if (deposit > price) {
            event.preventDefault();
            alert('Tiền cọc không được lớn hơn tiền thuê phòng.');
            fields.deposit.focus();
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
