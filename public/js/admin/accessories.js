document.addEventListener("DOMContentLoaded", function () {
    const openBtn = document.getElementById("btn-open-create-form");
    const closeBtn = document.getElementById("btn-cancel-create");
    const formOverlay = document.getElementById("create-form");

    if (openBtn && closeBtn && formOverlay) {
        openBtn.addEventListener("click", () => {
            formOverlay.style.display = "flex";
        });

        closeBtn.addEventListener("click", () => {
            formOverlay.style.display = "none";
        });

        // Đóng khi click ra ngoài form
        formOverlay.addEventListener("click", function (e) {
            if (e.target === formOverlay) {
                formOverlay.style.display = "none";
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const openCreateBtn = document.getElementById('btn-open-create-form');
    const createModal = document.getElementById('create-form-modal');
    const editModal = document.getElementById('edit-form-modal');
    const closeBtns = document.querySelectorAll('.close-modal');
    const cancelBtn = document.querySelector('.btn-cancel');

    // Mở tạo mới
    openCreateBtn?.addEventListener('click', () => {
        createModal.style.display = 'flex';
    });

    // Đóng modal
    function closeAll() {
        createModal.style.display = 'none';
        editModal.style.display = 'none';
    }
    closeBtns.forEach(b => b.addEventListener('click', closeAll));
    cancelBtn?.addEventListener('click', closeAll);
    window.addEventListener('click', (e) => {
        if (e.target === createModal || e.target === editModal) closeAll();
    });

    // Preview ảnh tạo mới
    const imageInput = document.getElementById('image-input');
    const preview = document.getElementById('preview');
    imageInput?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    });

    // Open edit modal và fill data
    document.querySelectorAll('.open-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = document.getElementById('edit-form');

            form.action = btn.dataset.updateUrl;
            document.getElementById('edit-name').value = btn.dataset.name || '';
            document.getElementById('edit-price').value = btn.dataset.price || 0;
            document.getElementById('edit-quantity').value = btn.dataset.quantity || 0;
            const desc = document.getElementById('edit-description');
            desc.value = btn.dataset.description || '';

            // material, color nếu có
            const mat = document.getElementById('edit-material');
            if (mat) mat.value = btn.dataset.material || '';
            const color = document.getElementById('edit-color');
            if (color) color.value = btn.dataset.color || '';

            // preview ảnh cũ
            const editPreview = document.getElementById('edit-preview');
            editPreview.src = btn.dataset.image;
            editPreview.style.display = 'block';

            editModal.style.display = 'flex';
        });
    });

    // Preview ảnh khi chọn ảnh mới trong modal edit
    const editImageInput = document.getElementById('edit-image-input');
    editImageInput?.addEventListener('change', (e) => {
        const file = e.target.files?.[0];
        if (!file) return;
        const editPreview = document.getElementById('edit-preview');
        editPreview.src = URL.createObjectURL(file);
        editPreview.style.display = 'block';
    });
});
