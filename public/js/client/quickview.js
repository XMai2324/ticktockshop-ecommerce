// public/js/quickview.js

(function () {
  function initQuickView() {
    // ====== Event delegation ======
    document.addEventListener('click', async (event) => {
      const modal     = document.getElementById('quickViewModal');
      const modalBody = document.getElementById('quick-view-body');
      if (!modal || !modalBody) return;

      // Config từ <meta>
      const csrf        = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const slugPattern = document.querySelector('meta[name="quickview-slug-pattern"]')?.content || '/quick-view/{slug}';
      const accPattern  = document.querySelector('meta[name="quickview-acc-pattern"]')?.content  || '/accessories/quick-view/{type}/{id}';
      const cartAddUrl  = document.querySelector('meta[name="cart-add-url"]')?.content || '/add-to-cart';

      // ===== UI helpers =====
      function openModal(html) {
        modalBody.innerHTML = html;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        modal.querySelector('.close-modal')?.focus();
      }
      function closeModal() {
        modal.style.display = 'none';
        modalBody.innerHTML = '';
        document.body.style.overflow = '';
      }

      function updateCartIcon(quantity) {
        const cartIcon = document.querySelector('.cart-icon');
        if (!cartIcon) return;
        let cartCount = document.querySelector('.cart-count');
        const qty = Number(quantity) || 0;
        if (qty > 0) {
          if (!cartCount) {
            cartCount = document.createElement('span');
            cartCount.className = 'cart-count';
            cartIcon.appendChild(cartCount);
          }
          cartCount.textContent = qty;
        } else {
          cartCount?.remove();
        }
      }

      async function fetchQuickView({ slug, id, type }) {
        let url = '';
        if (slug) {
          url = slugPattern.replace('{slug}', encodeURIComponent(slug));
        } else if (id && type) {
          url = accPattern
            .replace('{type}', encodeURIComponent(type))
            .replace('{id}', encodeURIComponent(id));
        }
        if (!url) return null;

        const res = await fetch(url, { headers: { Accept: 'text/html' }, credentials: 'same-origin' });
        if (!res.ok) throw new Error(`Fetch quick view failed (${res.status})`);
        return res.text();
      }

      async function addToCart({ id, type, quantity }) {
        const res = await fetch(cartAddUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf
          },
          body: JSON.stringify({ id, type, quantity })
        });

        let data = null;
        try { data = await res.json(); } catch {}
        if (!res.ok || !data) throw new Error('Add to cart failed');
        return data;
      }

      // --- 1. Mở quick view ---
      const trigger = event.target.closest('.product-quick-view, .accessory-quick-view');
      if (trigger) {
        event.preventDefault();
        try {
          const html = await fetchQuickView({
            slug: trigger.dataset.slug,
            id: trigger.dataset.id,
            type: trigger.dataset.type
          });
          if (html) openModal(html);
        } catch (e) {
          console.error(e);
          alert('Không tải được chi tiết sản phẩm!');
        }
        return;
      }

      // --- 2. Đóng modal ---
      if (event.target.classList.contains('close-modal') || event.target === modal) {
        closeModal();
        return;
      }

      // --- 3. Thêm vào giỏ hàng ---
      const addBtn = event.target.closest('.btn-add-to-cart');
      if (addBtn) {
        const quantityInput = modal.querySelector('#quantity');
        const quantity = Math.max(1, parseInt(quantityInput?.value || '1', 10));
        try {
          const data = await addToCart({
            id: addBtn.dataset.id,
            type: addBtn.dataset.type,
            quantity
          });
          if (data?.success) {
            updateCartIcon(data.cart_count);
            alert('Đã thêm vào giỏ hàng!');
            closeModal();
          } else {
            alert(data?.message || 'Thêm vào giỏ hàng thất bại!');
          }
        } catch (e) {
          console.error(e);
          alert('Lỗi khi thêm vào giỏ hàng!');
        }
        return;
      }
    });

    // ====== ESC để đóng ======
    document.addEventListener('keydown', (e) => {
      const modal = document.getElementById('quickViewModal');
      const modalBody = document.getElementById('quick-view-body');
      if (e.key === 'Escape' && modal?.style.display === 'block') {
        modal.style.display = 'none';
        modalBody.innerHTML = '';
        document.body.style.overflow = '';
      }
    });
  }

  // ====== Gắn init cho nhiều tình huống load trang ======
  document.addEventListener('DOMContentLoaded', initQuickView);
  document.addEventListener('pageshow', initQuickView);        // back/forward cache
  document.addEventListener('turbo:load', initQuickView);      // Hotwire Turbo
  document.addEventListener('turbolinks:load', initQuickView); // Turbolinks
  document.addEventListener('pjax:complete', initQuickView);   // PJAX
})();
