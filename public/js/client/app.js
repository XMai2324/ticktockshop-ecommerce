
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
});