(() => {
  'use strict';

  /* ========= Helpers & Selectors ========= */
  const $  = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

  const csrf       = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  const APPLY_URL  = window.CHECKOUT_ROUTES?.apply  || '';
  const REMOVE_URL = window.CHECKOUT_ROUTES?.remove || '';
  const modal      = $('#coupon-modal');

  // format tiền VN
  const vn = n => Number(n || 0).toLocaleString('vi-VN');

  // trạng thái đăng nhập (từ Blade)
  const isAuthed = () =>
    (typeof IS_AUTHENTICATED !== 'undefined' ? !!IS_AUTHENTICATED : !!window.IS_AUTHENTICATED);

  // mở form đăng nhập
  function openLogin() {
    const loginIcon = document.getElementById('login-icon');
    if (loginIcon) {
      loginIcon.click();
    } else {
      const overlay = document.getElementById('login-overlay');
      overlay?.classList?.remove('hidden');
      document.body.classList?.add('no-scroll');
    }
  }

  /* ========= Modal coupon ========= */
  function openModal() {
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.classList.add('no-scroll');
  }
  function closeModal() {
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.classList.remove('no-scroll');
  }
  function bindOpenButton() {
    $('#btn-open-coupon')?.addEventListener('click', openModal);
  }

  // init
  bindOpenButton();

  // đóng modal bằng overlay hoặc nút X
  modal?.addEventListener('click', (e) => {
    if (e.target.dataset.close === 'coupon-modal' || e.target.classList.contains('modal__close')) {
      closeModal();
    }
  });

  /* ========= Áp dụng mã ========= */
  $('#coupon-list')?.addEventListener('click', async (e) => {
    if (!e.target.classList.contains('use-coupon')) return;

    // chưa đăng nhập -> alert -> đóng modal -> mở login
    if (!isAuthed()) {
      alert('Vui lòng đăng nhập để sử dụng mã khuyến mãi.');
      closeModal();
      requestAnimationFrame(openLogin);
      return;
    }

    const card = e.target.closest('.coupon-card');
    const code = card?.dataset.code;
    if (!code || !APPLY_URL) return;

    e.target.disabled = true;
    try {
      const res = await fetch(APPLY_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ code })
      });

      if (res.status === 401) {
        alert('Vui lòng đăng nhập để sử dụng mã khuyến mãi.');
        closeModal();
        requestAnimationFrame(openLogin);
        return;
      }

      const data = await res.json();
      if (!res.ok) throw new Error(data.message || 'Không áp dụng được mã');

      // cập nhật giảm giá
      const discountP = $('#discount-amount');
      if (discountP) {
        discountP.dataset.raw = data.discount_amount;
        discountP.innerHTML = '-' + vn(data.discount_amount) + ' <sup>đ</sup>';
      }
      // cập nhật tổng
      const grandP = $('#grand-total-text');
      if (grandP) {
        grandP.setAttribute('data-base', data.grand_total);
        grandP.innerHTML = vn(data.grand_total) + ' <sup>đ</sup>';
      }
      // hiển thị mã đã áp dụng
      const holder = document.querySelector('.coupon-area .value');
      if (holder) {
        holder.innerHTML = `
          <span id="applied-coupon">${data.coupon.name} (${data.coupon.code})</span>
          <button type="button" id="btn-remove-coupon" class="btn-link small">Bỏ mã</button>
        `;
      }

      closeModal();
    } catch (err) {
      alert(err.message);
    } finally {
      e.target.disabled = false;
    }
  });

  /* ========= Bỏ mã (delegated) ========= */
  document.addEventListener('click', async (e) => {
    if (e.target.id !== 'btn-remove-coupon') return;
    if (!REMOVE_URL) return;

    if (!isAuthed()) {
      alert('Vui lòng đăng nhập để thao tác với mã khuyến mãi.');
      requestAnimationFrame(openLogin);
      return;
    }

    e.target.disabled = true;
    try {
      const res = await fetch(REMOVE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        }
      });

      if (res.status === 401) {
        alert('Vui lòng đăng nhập để thao tác với mã khuyến mãi.');
        requestAnimationFrame(openLogin);
        return;
      }

      const data = await res.json();
      if (!res.ok) throw new Error(data.message || 'Không bỏ được mã');

      // reset giảm giá
      const discountP = $('#discount-amount');
      if (discountP) {
        discountP.dataset.raw = 0;
        discountP.innerHTML = '-0 <sup>đ</sup>';
      }
      // cập nhật tổng
      const grandP = $('#grand-total-text');
      if (grandP) {
        grandP.setAttribute('data-base', data.grand_total);
        grandP.innerHTML = vn(data.grand_total) + ' <sup>đ</sup>';
      }
      // trả lại nút chọn mã & bind lại open
      const holder = document.querySelector('.coupon-area .value');
      if (holder) {
        holder.innerHTML = `<button type="button" id="btn-open-coupon" class="btn primary">Chọn mã khuyến mãi</button>`;
        bindOpenButton();
      }
    } catch (err) {
      alert(err.message);
    } finally {
      e.target.disabled = false;
    }
  });

  /* ========= YÊU CẦU ĐĂNG NHẬP KHI THANH TOÁN ========= */
  (function requireLoginOnSubmit() {
    const form = document.getElementById('checkout-form');
    form?.addEventListener('submit', (e) => {
      if (!isAuthed()) {
        e.preventDefault();
        alert('Vui lòng đăng nhập để thanh toán.');
        requestAnimationFrame(openLogin);
      }
    });
  })();

})();
