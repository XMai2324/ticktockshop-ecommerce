// public/js/quickview.js


(function () {
  // ====== 1) Redirect old quickview triggers to the new detail page ======
  function initRedirectFromLegacyQuickView() {
    document.addEventListener('click', (event) => {
      const trigger = event.target.closest('.product-quick-view, .accessory-quick-view');
      if (!trigger) return;

      // Nếu link có href thật → cho trình duyệt xử lý bình thường
      if (trigger.tagName === 'A' && trigger.getAttribute('href') && trigger.getAttribute('href') !== 'javascript:void(0);') {
        return;
      }

      const productPattern = (document.querySelector('meta[name="product-detail-pattern"]')?.content || '/product/{slug}').trim();
      const slug = trigger.dataset.slug;
      if (slug) {
        event.preventDefault();
        const href = productPattern.replace('{slug}', encodeURIComponent(slug));
        window.location.href = href;
      }
    });
  }

  // ====== 2) Product detail gallery: thumbnails + prev/next ======
function initProductDetailGallery() {
  const gallery   = document.getElementById('gallery');
  const mainImage = document.getElementById('mainImage');
  if (!gallery || !mainImage) return;

  const thumbs = Array.from(gallery.querySelectorAll('.thumb img'));
  if (!thumbs.length) return;

  const prevBtn = gallery.querySelector('.gallery-prev');
  const nextBtn = gallery.querySelector('.gallery-next');

  // vị trí hiện tại (ưu tiên thumb đang .active)
  let current = Math.max(
    0,
    thumbs.findIndex(img => img.closest('.thumb')?.classList.contains('active'))
  );

  const setImage = (index) => {
    if (index < 0) index = thumbs.length - 1;
    if (index >= thumbs.length) index = 0;
    current = index;

    const target = thumbs[index];
    const nextSrc = target.dataset.large || target.src;

    mainImage.src = nextSrc;

    thumbs.forEach(t => t.closest('.thumb')?.classList.remove('active'));
    target.closest('.thumb')?.classList.add('active');
  };

  // click thumbnail
  thumbs.forEach((img, i) => img.addEventListener('click', () => setImage(i)));

  // nút trái/phải
  if (prevBtn) prevBtn.addEventListener('click', (e) => { e.preventDefault(); setImage(current - 1); });
  if (nextBtn) nextBtn.addEventListener('click', (e) => { e.preventDefault(); setImage(current + 1); });

  // khởi tạo
  setImage(current < 0 ? 0 : current);
}

  // ====== 3) Filters & sorting ======
  function initFiltersAndSort() {
    const priceSelect = document.querySelector('select[name="price_range"]');
    const sortSelect  = document.querySelector('select[name="sort"]');

    function updateQuery(param, value) {
      const url = new URL(window.location.href);
      if (value) url.searchParams.set(param, value);
      else url.searchParams.delete(param);
      url.searchParams.delete('page');
      window.location.href = url.toString();
    }

    if (priceSelect) {
      priceSelect.addEventListener('change', (e) => updateQuery('price_range', e.target.value));
    }
    if (sortSelect) {
      sortSelect.addEventListener('change', (e) => updateQuery('sort', e.target.value));
    }
  }

  // ====== Boot ======
  function boot() {
    initRedirectFromLegacyQuickView();
    initProductDetailGallery();
    initFiltersAndSort();
  }

  document.addEventListener('DOMContentLoaded', boot);
})();
