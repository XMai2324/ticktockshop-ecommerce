// public/js/admin/accessories.js

document.addEventListener('DOMContentLoaded', function () {
    const csrfTokenTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfTokenTag ? csrfTokenTag.getAttribute('content') : '';

    const createModal   = document.getElementById('accessory-create-modal');
    const editModal     = document.getElementById('accessory-edit-modal');
    const btnOpenCreate = document.getElementById('btn-open-accessory-create');

    // ====== Helper mở / đóng modal ======
    function openModal(modal) {
        if (!modal) return;
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function closeModal(modal) {
        if (!modal) return;
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    // Mở modal THÊM
    if (btnOpenCreate && createModal) {
        btnOpenCreate.addEventListener('click', function () {
            const form = createModal.querySelector('form');
            if (form) form.reset();

            const preview = document.getElementById('accessory-preview');
            if (preview) {
                preview.style.display = 'none';
                preview.src = '';
            }

            openModal(createModal);
        });
    }

    // Đóng modal khi bấm nút X
    document.querySelectorAll('.close-modal').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetSelector = btn.getAttribute('data-close');
            if (!targetSelector) return;
            const modal = document.querySelector(targetSelector);
            closeModal(modal);
        });
    });

    // Đóng modal khi click ra ngoài .modal-content
    document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                closeModal(overlay);
            }
        });
    });

    // ====== Preview ảnh khi THÊM ======
    const createImageInput = document.getElementById('accessory-image-input');
    const createPreview    = document.getElementById('accessory-preview');

    if (createImageInput && createPreview) {
        createImageInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                createPreview.style.display = 'none';
                createPreview.src = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                createPreview.src = e.target.result;
                createPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    // ====== Preview ảnh khi SỬA ======
    const editImageInput = document.getElementById('edit-accessory-image-input');
    const editPreview    = document.getElementById('edit-accessory-preview');

    if (editImageInput && editPreview) {
        editImageInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                editPreview.style.display = 'none';
                editPreview.src = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                editPreview.src = e.target.result;
                editPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    // ====== Mở modal SỬA, fill dữ liệu từ data-* ======
    const editNameInput        = document.getElementById('edit-accessory-name');
    const editPriceInput       = document.getElementById('edit-accessory-price');
    const editDescriptionInput = document.getElementById('edit-accessory-description');
    const editMaterialInput    = document.getElementById('edit-accessory-material');
    const editColorInput       = document.getElementById('edit-accessory-color');
    const editQuantityInput    = document.getElementById('edit-accessory-quantity');
    const editForm             = document.getElementById('accessory-edit-form');

    document.querySelectorAll('.btn-accessory-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id          = this.dataset.id;
            const type        = this.dataset.type;            // 'straps' | 'boxes' | 'glasses'
            const name        = this.dataset.name || '';
            const price       = this.dataset.price || 0;
            const description = this.dataset.description || '';
            const material    = this.dataset.material || '';
            const color       = this.dataset.color || '';
            const quantity    = this.dataset.quantity || '';

            if (editNameInput)        editNameInput.value        = name;
            if (editPriceInput)       editPriceInput.value       = price;
            if (editDescriptionInput) editDescriptionInput.value = description;
            if (editMaterialInput)    editMaterialInput.value    = material;
            if (editColorInput)       editColorInput.value       = color;
            if (editQuantityInput)    editQuantityInput.value    = quantity;

            // Reset preview ảnh mới
            if (editPreview) {
                editPreview.style.display = 'none';
                editPreview.src = '';
            }
            if (editImageInput) {
                editImageInput.value = '';
            }

            // Route::put('/accessories/{type}/{id}')
            if (editForm && id && type) {
                editForm.action = `/admin/accessories/${type}/${id}`;
            }

            openModal(editModal);
        });
    });

    // ====== ẨN / HIỆN phụ kiện (AJAX + badge "ĐANG ẨN") ======
    const toggleButtons = document.querySelectorAll('.btn-toggle-accessory');

    toggleButtons.forEach(btn => {
        const id   = btn.dataset.id;
        const type = btn.dataset.type; // straps / boxes / glasses
        const card = document.getElementById('accessory-' + id);
        if (!card) return;

        const badge = card.querySelector('.hidden-badge');

        function applyState(isHidden) {
            // cập nhật nút
            btn.textContent = isHidden ? 'Hiện' : 'Ẩn';
            btn.dataset.hidden = isHidden ? '1' : '0';

            // cập nhật card
            card.classList.toggle('accessory-hidden', isHidden);
            card.dataset.hidden = isHidden ? '1' : '0';

            // badge "ĐANG ẨN"
            if (badge) {
                badge.style.display = isHidden ? 'inline-block' : 'none';
            }
        }

        // Khởi tạo giao diện theo data-hidden ban đầu
        const initialHidden = btn.dataset.hidden === '1';
        applyState(initialHidden);

        // Click toggle
        btn.addEventListener('click', async function () {
            if (!csrfToken) {
                alert('Thiếu CSRF token!');
                return;
            }

            btn.disabled = true;

            try {
                const res = await fetch(`/admin/accessories/toggle/${type}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({}),
                });

                if (!res.ok) {
                    throw new Error('Lỗi server: ' + res.status);
                }

                const data = await res.json();
                const isHidden = !!data.hidden;
                applyState(isHidden);

            } catch (e) {
                console.error(e);
                alert('Có lỗi khi đổi trạng thái ẩn/hiện phụ kiện!');
            } finally {
                btn.disabled = false;
            }
        });
    });

    // ====== XÓA phụ kiện (confirm JS) ======
    document.querySelectorAll('form.form-delete-accessory').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Bạn có chắc muốn xoá phụ kiện này?')) {
                e.preventDefault();
            }
        });
    });

    // ========== FILTER ẨN / HIỆN ==========
    const btnFilterHidden = document.getElementById('btn-filter-hidden');

    if (btnFilterHidden) {
        let filterOn = false; // mặc định: tắt bộ lọc

        btnFilterHidden.addEventListener('click', function () {
            filterOn = !filterOn;

            // Cập nhật giao diện nút
            btnFilterHidden.classList.toggle('active', filterOn);
            btnFilterHidden.textContent = filterOn
                ? 'Chỉ hiển thị sản phẩm ẩn'
                : 'Hiện sản phẩm ẩn';

            // Lọc từng card sản phẩm
            document.querySelectorAll('.product-card').forEach(function (card) {
                const hidden = card.dataset.hidden === '1';

                if (filterOn) {
                    // Bật lọc → chỉ hiện sản phẩm ẩn
                    card.style.display = hidden ? 'block' : 'none';
                } else {
                    // Tắt lọc → hiện tất cả (ẩn thì vẫn bị làm mờ bằng CSS)
                    card.style.display = 'block';
                }
            });
        });
    }
});

    
