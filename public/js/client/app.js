

    // LỌC SẮP XẾP & KHOẢNG GIÁ
document.addEventListener('DOMContentLoaded', function () {

    // Lọc theo SẮP XẾP
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function () {
            const value = this.value;
            const url = new URL(window.location.href);

            if (value === 'asc' || value === 'desc') {
                url.searchParams.set('sort', value);
            } else {
                url.searchParams.delete('sort');
            }

            window.location.href = url.toString();
        });
    }

    // Lọc theo KHOẢNG GIÁ
    const priceSelect = document.querySelector('select[name="price_range"]');
    if (priceSelect) {
        priceSelect.addEventListener('change', function () {
            const value = this.value;
            const url = new URL(window.location.href);

            if (value) {
                url.searchParams.set('price_range', value);
            } else {
                url.searchParams.delete('price_range');
            }

            window.location.href = url.toString();
        });
    }

    // ===============================
    // 2. XEM NHANH SẢN PHẨM (MODAL)
    // ===============================
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modal-product-content');
    const closeModal = document.querySelector('.close-modal');

    if (modal && modalContent && closeModal) {
        document.querySelectorAll('.product-quick-view').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const slug = this.dataset.slug;

                fetch(`/quick-view/${slug}`)
                    .then(res => res.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                        modal.style.display = 'flex';
                    });
            });
        });

        closeModal.addEventListener('click', () => modal.style.display = 'none');
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
});