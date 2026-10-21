
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('propertyModal');

            if (modalElement) {
                const hasErrors = modalElement.querySelector('.is-invalid');

                if (hasErrors && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            }
        });
