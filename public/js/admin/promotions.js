// public/js/admin/promotions.js

// Thêm vào đầu phần cache:
const fStart  = document.getElementById('f-start');
const fEnd    = document.getElementById('f-end');
const fActive = document.getElementById('f-active');

// Hàm định dạng chuỗi datetime của DB -> input datetime-local
function toDatetimeLocal(str) {
  // chấp nhận "2025-09-10 14:04:37" hoặc ISO "2025-09-10T14:04:37Z"
  if (!str) return '';
  const d = new Date(str.replace(' ', 'T'));
  if (isNaN(d.getTime())) return '';
  const pad = n => String(n).padStart(2, '0');
  const yyyy = d.getFullYear();
  const mm   = pad(d.getMonth() + 1);
  const dd   = pad(d.getDate());
  const hh   = pad(d.getHours());
  const mi   = pad(d.getMinutes());
  return `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
}

function toCreateMode() {
  form.reset();
  if (titleEl) titleEl.textContent = 'Thêm khuyến mãi';
  if (btnSubmit) btnSubmit.textContent = 'Lưu';
  form.setAttribute('action', defaultAction);
  if (hiddenMethodInput) { hiddenMethodInput.remove(); hiddenMethodInput = null; }
  if (fActive) fActive.checked = true;
  enableMaxDiscountIfPercent();
}
window.resetPromotionForm = toCreateMode;

// Trong window.editPromotion, sau khi set các field khác, thêm:
if (fStart)  fStart.value  = toDatetimeLocal(p.start_at);
if (fEnd)    fEnd.value    = toDatetimeLocal(p.end_at);
if (fActive) fActive.checked = !!p.is_active;


(function () {
  const form = document.getElementById('promotion-form');
  if (!form) return;

  const titleEl       = document.getElementById('form-title');
  const btnSubmit     = form.querySelector('button[type="submit"], .btn-primary') || form.querySelector('button');
  const defaultAction = form.getAttribute('action');

  const fName         = document.getElementById('f-name');
  const fCode         = document.getElementById('f-code');
  const fType         = document.getElementById('f-type');
  const fValue        = document.getElementById('f-value');
  const fMaxDiscount  = document.getElementById('f-max-discount');
  const fMinOrder     = document.getElementById('f-min-order');
  const fUsageLimit   = document.getElementById('f-usage-limit');
  const fPerUserLimit = document.getElementById('f-per-user-limit');

  let hiddenMethodInput = null;

  function enableMaxDiscountIfPercent() {
    const isPercent = fType.value === 'percent';
    fMaxDiscount.disabled = !isPercent;
    fMaxDiscount.required = isPercent;
    if (!isPercent) fMaxDiscount.value = '';
  }

  function scrollToForm() {
    const leftPanel = document.querySelector('.panel-left');
    if (leftPanel) leftPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    if (fName) fName.focus();
  }

  function toCreateMode() {
    form.reset();
    if (titleEl) titleEl.textContent = 'Thêm khuyến mãi';
    if (btnSubmit) btnSubmit.textContent = 'Lưu';
    form.setAttribute('action', defaultAction);
    if (hiddenMethodInput) {
      hiddenMethodInput.remove();
      hiddenMethodInput = null;
    }
    enableMaxDiscountIfPercent();
  }

  function safeNumber(v) {
    if (v === null || v === undefined) return '';
    return String(v);
  }

  // Expose global for inline onclick in Blade
  window.editPromotion = function (p, updateUrl) {
    try {
      if (typeof p === 'string') p = JSON.parse(p);
    } catch (e) {}

    fName.value         = p.name ?? '';
    fCode.value         = p.code ?? '';
    fType.value         = p.type ?? 'percent';
    fValue.value        = safeNumber(p.value);
    fMaxDiscount.value  = safeNumber(p.max_discount);
    fMinOrder.value     = safeNumber(p.min_order_value ?? 0);
    fUsageLimit.value   = safeNumber(p.usage_limit);
    fPerUserLimit.value = safeNumber(p.per_user_limit);

    enableMaxDiscountIfPercent();

    if (titleEl) titleEl.textContent = 'Sửa khuyến mãi';
    if (btnSubmit) btnSubmit.textContent = 'Cập nhật';

    if (!hiddenMethodInput) {
      hiddenMethodInput = document.createElement('input');
      hiddenMethodInput.type = 'hidden';
      hiddenMethodInput.name = '_method';
      hiddenMethodInput.value = 'PUT';
      form.appendChild(hiddenMethodInput);
    }

    if (updateUrl) {
      form.setAttribute('action', updateUrl);
    } else {
      const guess = defaultAction.replace(/\/?$/, '/') + (p.id ?? '');
      form.setAttribute('action', guess);
    }

    scrollToForm();
  };

  // Toggle max_discount when type changes
  if (fType) fType.addEventListener('change', enableMaxDiscountIfPercent);

  // Soft validate before submit
  form.addEventListener('submit', function (e) {
    if (fType.value === 'percent') {
      const v = Number(fValue.value);
      if (isNaN(v) || v <= 0 || v > 100) {
        e.preventDefault();
        alert('Giá trị phần trăm phải trong khoảng 1 đến 100.');
        fValue.focus();
        return;
      }
      if (!fMaxDiscount.value) {
        e.preventDefault();
        alert('Vui lòng nhập Giảm tối đa khi chọn Giảm %.');
        fMaxDiscount.focus();
        return;
      }
    }
  });

  // Public helper to reset tạo mới nếu cần
  window.resetPromotionForm = toCreateMode;

  // Init state
  enableMaxDiscountIfPercent();
})();
