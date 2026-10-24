
        document.getElementById('images').addEventListener('change', function () {
            const preview = document.getElementById('imagePreview');
            const files = Array.from(this.files);

            preview.innerHTML = '';

            if (files.length > 10) {
                alert('Chỉ được chọn tối đa 10 ảnh mới.');
                this.value = '';
                return;
            }

            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const reader = new FileReader();

                reader.onload = function (event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = 'Ảnh mới';
                    img.className = 'rental-preview-image';
                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            });
        });
