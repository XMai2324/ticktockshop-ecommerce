document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.getElementById('btn-open-create-form');
    const modal = document.getElementById('create-form-modal');
    const closeBtn = modal.querySelector('.close-modal');
    const input = document.getElementById('image-input');
    const preview = document.getElementById('preview');

    // Mở modal
    if (openBtn && modal && closeBtn) {
        openBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // Xem trước ảnh
    if (input && preview) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
