
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
                            'Mày đã đánh dấu ' + deletedImages +
                            ' ảnh cần xóa. Tiếp tục lưu thay đổi nhé?'
                        );

                        if (!confirmed) {
                            event.preventDefault();
                        }
                    }
                });
            }
        });
