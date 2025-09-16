document.addEventListener('DOMContentLoaded', function () {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const cartUpdateUrl = document.querySelector('meta[name="cart-update-url"]')?.getAttribute('content') || '/cart/update';

  document.querySelectorAll('.qty-input').forEach(inp => {
    inp.addEventListener('change', e => {
      const qty = Math.max(1, parseInt(e.target.value || '1', 10));
      const rowId = e.target.dataset.rowId;
      if (!rowId) return;

      fetch(cartUpdateUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ row_id: rowId, qty })
      })
      .then(r => r.json())
      .then(data => {
        if (!data.ok) return;

        // cập nhật thành tiền dòng
        const sub = document.getElementById('subtotal-' + rowId);
        if (sub) sub.textContent = data.rowSubtotal + ' đ';

        // cập nhật tổng tiền
        const total = document.getElementById('cart-total');
        if (total) total.textContent = data.cartTotal + ' đ';

        const totalGoods = document.getElementById('cart-total-goods');
        if (totalGoods) totalGoods.textContent = data.cartTotal + ' đ';

        // cập nhật tổng sản phẩm
        const totalQty = document.getElementById('cart-total-qty');
        if (totalQty) totalQty.textContent = data.totalQty;

        // cập nhật badge icon giỏ
        const badge = document.getElementById('cart-count');
        if (badge) badge.textContent = data.totalQty;
      })
      .catch(console.error);
    });
  });
});
