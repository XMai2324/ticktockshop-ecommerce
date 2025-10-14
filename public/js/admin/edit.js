document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".btn-edit");
    const editModal = document.getElementById("edit-form-modal");
    const closeEditBtn = editModal.querySelector(".close-modal");

    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Lấy dữ liệu từ thẻ a
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = this.dataset.price;
            const description = this.dataset.description;
            const brand = this.dataset.brand;
            const category = this.dataset.category;
            const image = this.dataset.image;
            const folder = this.dataset.folder;

            // Gán vào form
            document.getElementById("edit-name").value = name;
            document.getElementById("edit-price").value = price;
            document.getElementById("edit-description").value = description;
            document.getElementById("edit-brand").value = brand;
            document.getElementById("edit-category").value = category;

            // Ảnh preview từ server
            const preview = document.getElementById("edit-preview");
            preview.src = `/storage/${folder}/${image}`;
            preview.style.display = "block";

            // Gán action cho form
            const form = document.getElementById("edit-form");
            form.action = `/admin/products/${id}`;

            // Hiện modal
            editModal.classList.add("active");
        });
    });

    // Đóng modal
    closeEditBtn.addEventListener("click", () => {
        editModal.classList.remove("active");
    });

    editModal.addEventListener("click", function (e) {
        if (e.target === editModal) {
            editModal.classList.remove("active");
        }
    });

    // Xem trước ảnh mới khi người dùng chọn file
    const editInput = document.getElementById('edit-image-input');
    const editPreview = document.getElementById('edit-preview');

    if (editInput && editPreview) {
        editInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    editPreview.src = e.target.result;
                    editPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
