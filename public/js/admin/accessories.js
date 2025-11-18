document.addEventListener("DOMContentLoaded", function () {
    // mở modal thêm
    const btnOpenCreate = document.getElementById("btn-open-accessory-create");
    const createModal   = document.getElementById("accessory-create-modal");
    const editModal     = document.getElementById("accessory-edit-modal");

    if (btnOpenCreate && createModal) {
        btnOpenCreate.addEventListener("click", () => {
            createModal.style.display = "block";
        });
    }

    // đóng modal
    document.querySelectorAll(".close-modal").forEach(btn => {
        btn.addEventListener("click", function () {
            const target = this.getAttribute("data-close");
            const modal = document.querySelector(target);
            if (modal) modal.style.display = "none";
        });
    });

    // click ngoài modal để đóng
    window.addEventListener("click", function (e) {
        [createModal, editModal].forEach(m => {
            if (m && e.target === m) m.style.display = "none";
        });
    });

    // preview ảnh thêm mới
    const createImgInput = document.getElementById("accessory-image-input");
    const createPreview  = document.getElementById("accessory-preview");
    if (createImgInput && createPreview) {
        createImgInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => {
                createPreview.src = ev.target.result;
                createPreview.style.display = "block";
            };
            reader.readAsDataURL(file);
        });
    }

    // preview ảnh sửa
    const editImgInput = document.getElementById("edit-accessory-image-input");
    const editPreview  = document.getElementById("edit-accessory-preview");
    if (editImgInput && editPreview) {
        editImgInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = ev => {
                editPreview.src = ev.target.result;
                editPreview.style.display = "block";
            };
            reader.readAsDataURL(file);
        });
    }

    // nút Sửa: mở modal + fill data
    const editButtons = document.querySelectorAll(".btn-accessory-edit");
    const editForm    = document.getElementById("accessory-edit-form");

    editButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            const id    = this.dataset.id;
            const name  = this.dataset.name;
            const price = this.dataset.price;
            const desc  = this.dataset.description;

            document.getElementById("edit-accessory-name").value = name;
            document.getElementById("edit-accessory-price").value = price;
            document.getElementById("edit-accessory-description").value = desc || '';

            // set action cho form update: /admin/accessories/{type}/{id}
            editForm.action = `/admin/accessories/{{ $type }}/${id}`;

            editModal.style.display = "block";
        });
    });
});