document.addEventListener('DOMContentLoaded', () => {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const updateUrl =
    window.CART_UPDATE_URL ||
    document.querySelector('meta[name="cart-update-url"]')?.content ||
    '/cart/update';

  // ===== Helpers =====
  const asNumber = (v) => {
    if (typeof v === 'number') return v;
    if (typeof v === 'string') {
      // nhận cả "1.234.567" -> 1234567
      const s = v.replace(/[^\d]/g, '');
      const n = parseInt(s, 10);
      return Number.isFinite(n) ? n : 0;
    }
    return 0;
  };
  const money = (n) => `${Number(n || 0).toLocaleString('vi-VN')} <sub>đ</sub>`;

  function setCartBadge(count) {
    const c = asNumber(count);
    const icon = document.querySelector('.cart-icon');
    if (!icon) return;

    let badge = document.getElementById('cart-count');
    if (c > 0) {
      if (!badge) {
        badge = document.createElement('span');
        badge.id = 'cart-count';
        badge.className = 'cart-count';
        icon.appendChild(badge);
      }
      badge.textContent = c;
    } else {
      badge?.remove();
    }
  }

  function updateSummary({ totalQty, cartTotal }) {
    const qtyEl  = document.getElementById('cart-total-qty');
    const goods  = document.getElementById('cart-total-goods');
    const total  = document.getElementById('cart-total');
    if (totalQty != null && qtyEl) qtyEl.textContent = asNumber(totalQty);
    if (cartTotal != null) {
      const num = asNumber(cartTotal);
      if (goods) goods.innerHTML = money(num);
      if (total) total.innerHTML = money(num);
    }
  }

  function updateRowSubtotal(rowId, subtotal) {
    const subEl = document.getElementById('subtotal-' + rowId);
    if (!subEl) return;
    // hỗ trợ cả số hoặc chuỗi đã format
    if (typeof subtotal === 'number') subEl.innerHTML = money(subtotal);
    else subEl.innerHTML = `${subtotal} <sub>đ</sub>`.replace(/\s+<sub>đ<\/sub>.*$/, ' <sub>đ</sub>');
  }

  // ===== API =====
  async function apiUpdateQty(rowId, quantity) {
    const res = await fetch(updateUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ row_id: rowId, quantity })
    });
    const data = await res.json().catch(() => ({}));
    const ok = (data.success === true) || (data.ok === true);
    if (!res.ok || !ok) throw new Error(data.message || 'Cập nhật số lượng thất bại');
    return data; // { success, subtotal|rowSubtotal, cart_total|cartTotal, total_qty|totalQty, cart_count }
  }

  async function apiRemove(url) {
    const res = await fetch(url, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
      }
    });
    // Nếu backend redirect (không trả JSON), cố gắng parse; nếu fail thì coi như lỗi
    let data = null;
    try { data = await res.json(); } catch { /* ignore */ }
    if (!res.ok || !data || data.success !== true) {
      throw new Error(data?.message || 'Xoá sản phẩm thất bại');
    }
    return data; // { success, total_qty, cart_total, cart_count }
  }

  // ===== Debounce theo row =====
  const timers = new Map();
  function debounceRow(rowId, fn, delay = 350) {
    const t = timers.get(rowId);
    if (t) clearTimeout(t);
    const nt = setTimeout(fn, delay);
    timers.set(rowId, nt);
  }

  // ===== Quantity: input + change (event delegation) =====
  document.addEventListener('input', (e) => {
    const inp = e.target.closest('.qty-input');
    if (!inp) return;

    const rowId = inp.dataset.rowId;
    let qty = parseInt(inp.value, 10);
    if (!Number.isFinite(qty) || qty < 1) qty = 1;

    debounceRow(rowId, async () => {
      try {
        const data = await apiUpdateQty(rowId, qty);
        // ưu tiên số thuần 'subtotal', fallback 'rowSubtotal' đã format
        if (data.subtotal != null) updateRowSubtotal(rowId, data.subtotal);
        else if (data.rowSubtotal != null) updateRowSubtotal(rowId, data.rowSubtotal);

        updateSummary({
          totalQty:  data.total_qty ?? data.totalQty,
          cartTotal: data.cart_total ?? data.cartTotal
        });
        setCartBadge(data.cart_count ?? data.total_qty ?? data.totalQty);
      } catch (err) {
        console.error(err);
      }
    });
  });

  // blur/change: đảm bảo gửi nếu user chỉ đổi 1 lần rồi rời focus
  document.addEventListener('change', (e) => {
    const inp = e.target.closest('.qty-input');
    if (!inp) return;

    const rowId = inp.dataset.rowId;
    let qty = parseInt(inp.value, 10);
    if (!Number.isFinite(qty) || qty < 1) {
      qty = 1;
      inp.value = '1';
    }

    debounceRow(rowId, async () => {
      try {
        const data = await apiUpdateQty(rowId, qty);
        if (data.subtotal != null) updateRowSubtotal(rowId, data.subtotal);
        else if (data.rowSubtotal != null) updateRowSubtotal(rowId, data.rowSubtotal);

        updateSummary({
          totalQty:  data.total_qty ?? data.totalQty,
          cartTotal: data.cart_total ?? data.cartTotal
        });
        setCartBadge(data.cart_count ?? data.total_qty ?? data.totalQty);
      } catch (err) {
        console.error(err);
      }
    }, 0);
  });

  // Ngăn Enter trong input số làm submit/reload
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && e.target.closest('.qty-input')) e.preventDefault();
  });

  // ===== Remove item (event delegation) =====
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.remove-btn');
    if (!btn) return;

    e.preventDefault();
    const url = btn.dataset.url;
    if (!url) return console.error('Thiếu data-url cho nút xoá');

    const row = btn.closest('tr');
    btn.disabled = true;

    try {
      const data = await apiRemove(url);

      // Xoá dòng
      row?.remove();

      // Cập nhật tổng & badge
      updateSummary({ totalQty: data.total_qty, cartTotal: data.cart_total });
      setCartBadge(data.cart_count ?? data.total_qty);

      // Nếu giỏ rỗng, chèn dòng thông báo
      const tbody = document.querySelector('.cart-content-left table tbody') || document.querySelector('.cart-content-left table');
      if (tbody && !tbody.querySelector('tr')) {
        const tr = document.createElement('tr');
        tr.innerHTML = '<td colspan="6">Giỏ hàng trống.</td>';
        tbody.appendChild(tr);
      }
    } catch (err) {
      console.error(err);
      alert(err.message || 'Có lỗi khi xoá sản phẩm');
      btn.disabled = false;
    }
  });
});
