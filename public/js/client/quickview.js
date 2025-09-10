document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('quickViewModal');
    const modalBody = document.getElementById('quick-view-body');

    // ✅ Hàm cập nhật icon giỏ hàng
    function updateCartIcon(quantity) {
        const cartIcon = document.querySelector('.cart-icon');
        let cartCount = document.querySelector('.cart-count');

        if (quantity > 0) {
            if (!cartCount) {
                cartCount = document.createElement('span');
                cartCount.className = 'cart-count';
                cartIcon.appendChild(cartCount);
            }
            cartCount.textContent = quantity;
        } else if (cartCount) {
            cartCount.remove();
        }
    }

    // 👉 Gộp selector cho cả sản phẩm chính và phụ kiện
    document.querySelectorAll('.product-quick-view, .accessory-quick-view').forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault(); //  Ngăn reload trang
            const slug = this.dataset.slug;
            const id = this.dataset.id;
            const type = this.dataset.type;

            let fetchUrl = '';

            if (slug) {
                fetchUrl = `/quick-view/${slug}`;
            } else if (id && type) {
                fetchUrl = `/accessories/quick-view/${type}/${id}`;
            }

            if (!fetchUrl) return;

            fetch(fetchUrl)
                .then(res => res.text())
                .then(html => {
                    modalBody.innerHTML = html;
                    modal.style.display = 'block';
                })
                .catch(() => {
                    alert('Không tải được chi tiết sản phẩm!');
                });
        });
    });

    // ✅ Đóng modal + xử lý thêm giỏ hàng
    modal.addEventListener('click', function (event) {
        if (event.target.classList.contains('close-modal')) {
            modal.style.display = 'none';
            modalBody.innerHTML = '';
        }

        if (event.target.classList.contains('btn-add-to-cart')) {
            const productId = event.target.dataset.id;
            const productType = event.target.dataset.type;
            const quantityInput = modal.querySelector('#quantity');
            const quantity = quantityInput ? quantityInput.value : 1;

            fetch('/add-to-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: productId,
                    type: productType,
                    quantity: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Đã thêm vào giỏ hàng!');

                    // ✅ Cập nhật icon giỏ hàng
                    updateCartIcon(data.cart_count);

                    modal.style.display = 'none';
                    modalBody.innerHTML = '';
                } else {
                    alert('Thêm vào giỏ hàng thất bại!');
                }
            })
            .catch(() => {
                alert('Lỗi khi thêm vào giỏ hàng!');
            });
        }
    });

    // ✅ Click ra ngoài modal để tắt
    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            modalBody.innerHTML = '';
        }
    });
});