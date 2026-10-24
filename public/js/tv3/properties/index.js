
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
