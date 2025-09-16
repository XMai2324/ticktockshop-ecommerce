document.addEventListener('DOMContentLoaded', function () {
  // ===== Helpers: DOM =====
  const modal     = document.getElementById('quickViewModal');
  const modalBody = document.getElementById('quick-view-body');
  const csrf      = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  if (!modal || !modalBody) return;

  // ===== Helpers: URLs từ <meta> để tránh hardcode =====
  const slugPattern = document.querySelector('meta[name="quickview-slug-pattern"]')?.getAttribute('content') || '/quick-view/{slug}';
  const accPattern  = document.querySelector('meta[name="quickview-acc-pattern"]')?.getAttribute('content')  || '/accessories/quick-view/{type}/{id}';
  const cartAddUrl  = document.querySelector('meta[name="cart-add-url"]')?.getAttribute('content') || '/add-to-cart';

  // ===== UI state: mở/đóng modal =====
  function openModal(html) {
    modalBody.innerHTML = html;
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // khóa scroll nền
    // Focus phần tử đóng để hỗ trợ bàn phím
    const closeBtn = modal.querySelector('.close-modal');
    if (closeBtn) closeBtn.focus();
  }

  function closeModal() {
    modal.style.display = 'none';
    modalBody.innerHTML = '';
    document.body.style.overflow = ''; // trả lại scroll
  }

  // ===== Badge giỏ hàng =====
  function updateCartIcon(quantity) {
    const cartIcon = document.querySelector('.cart-icon');
    if (!cartIcon) return;

    let cartCount = document.querySelector('.cart-count');
    if (Number(quantity) > 0) {
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

  // ===== Lấy HTML Quick View =====
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

    const res = await fetch(url, { headers: { 'Accept': 'text/html' }, credentials: 'same-origin' });
    if (!res.ok) throw new Error('Fetch quick view failed');
    return res.text();
  }

  // ===== Thêm vào giỏ hàng =====
  async function addToCart({ id, type, quantity }) {
    const res = await fetch(cartAddUrl, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf
      },
      body: JSON.stringify({ id, type, quantity })
    });

    // Try parse JSON an toàn
    let data = null;
    try { data = await res.json(); } catch { /* ignore */ }

    if (!res.ok || !data) {
      throw new Error('Add to cart failed');
    }
    return data;
  }

  // ===== Event delegation cho click mở Quick View =====
  document.addEventListener('click', async function (event) {
    const trigger = event.target.closest('.product-quick-view, .accessory-quick-view');
    if (!trigger) return;

    event.preventDefault();
    const slug = trigger.dataset.slug;
    const id   = trigger.dataset.id;
    const type = trigger.dataset.type;

    try {
      const html = await fetchQuickView({ slug, id, type });
      if (!html) return;
      openModal(html);
    } catch (e) {
      console.error(e);
      alert('Không tải được chi tiết sản phẩm!');
    }
  });

  // ===== Event trong modal: đóng và thêm giỏ =====
  modal.addEventListener('click', async function (event) {
    // Đóng modal
    if (event.target.classList.contains('close-modal')) {
      closeModal();
      return;
    }

    // Thêm giỏ
    const addBtn = event.target.closest('.btn-add-to-cart');
    if (addBtn) {
      const productId   = addBtn.dataset.id;
      const productType = addBtn.dataset.type;

      const quantityInput = modal.querySelector('#quantity');
      const quantity = Math.max(1, parseInt(quantityInput ? quantityInput.value : '1', 10));

      try {
        const data = await addToCart({ id: productId, type: productType, quantity });
        if (data?.success) {
          updateCartIcon(data.cart_count);
          // Tuỳ anh: toast/alert
          alert('Đã thêm vào giỏ hàng!');
          closeModal();
        } else {
          alert(data?.message || 'Thêm vào giỏ hàng thất bại!');
        }
      } catch (e) {
        console.error(e);
        alert('Lỗi khi thêm vào giỏ hàng!');
      }
    }
  });

  // Click ra ngoài modal để tắt
  window.addEventListener('click', function (event) {
    if (event.target === modal) closeModal();
  });

  // Esc để đóng
  window.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modal.style.display === 'block') {
      closeModal();
    }
  });
});
