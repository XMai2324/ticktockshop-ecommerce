(function () {
  // Cache DOM
  const form           = document.getElementById('promotion-form');
  const leftPanel      = document.querySelector('.panel-left');
  const titleEl        = document.getElementById('form-title-left')
  const btnSubmit      = form ? form.querySelector('button[type="submit"], .btn-primary') : null;
  const defaultAction  = form ? form.getAttribute('action') : '';

  const fName          = document.getElementById('f-name');
  const fCode          = document.getElementById('f-code');
  const fType          = document.getElementById('f-type');
  const fValue         = document.getElementById('f-value');
  const fMaxDiscount   = document.getElementById('f-max-discount');
  const fMinOrder      = document.getElementById('f-min-order');
  const fUsageLimit    = document.getElementById('f-usage-limit');
  const fPerUserLimit  = document.getElementById('f-per-user-limit');
  const fStart         = document.getElementById('f-start');
  const fEnd           = document.getElementById('f-end');
  const fActive        = document.getElementById('f-active');

  let hiddenMethodInput = null;

  // Helper: convert DB datetime -> datetime-local input
  function toDatetimeLocal(str) {
    if (!str) return '';
    // Nếu là kiểu Carbon thì sẽ là yyyy-mm-dd hh:mm:ss
    // Nếu là kiểu JS thì sẽ là yyyy-mm-ddThh:mm:ss
    // Chuyển về yyyy-mm-ddThh:mm
    let d;
    if (typeof str === 'string') {
      d = new Date(str.replace(' ', 'T'));
      if (isNaN(d.getTime())) d = new Date(str); // thử lại nếu không hợp lệ
    } else {
      d = new Date(str);
    }
    if (isNaN(d.getTime())) return '';
    const pad = n => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
  }

  function enableMaxDiscountIfPercent() {
    if (!fType || !fMaxDiscount) return;
    const isPercent = fType.value === 'percent';
    fMaxDiscount.disabled = !isPercent;
    fMaxDiscount.required = isPercent;
    if (!isPercent) fMaxDiscount.value = '';
  }

  function scrollToForm() {
    if (leftPanel) leftPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    if (fName) fName.focus();
  }

  function safeNumber(v) {
    if (v === null || v === undefined) return '';
    if (typeof v === 'string' && v.trim() === '') return '';
    return String(v);
  }

  // Reset form về chế độ thêm mới
  function toCreateMode() {
    if (!form) return;
    form.reset();
    if (titleEl) titleEl.textContent = 'Thêm khuyến mãi';
    if (btnSubmit) btnSubmit.textContent = 'Lưu';
    form.setAttribute('action', defaultAction);
    if (hiddenMethodInput) { hiddenMethodInput.remove(); hiddenMethodInput = null; }
    if (fActive) fActive.checked = true;
    enableMaxDiscountIfPercent();
    if (leftPanel) leftPanel.style.display = '';
    scrollToForm();
  }
  window.resetPromotionForm = toCreateMode;

  // Edit khuyến mãi
  window.editPromotion = function (promotion, updateUrl) {
    console.log("editPromotion called", promotion, updateUrl);

    const form = document.getElementById("promotion-form");
    if (!form) {
        console.error("editPromotion: promotion-form not found in DOM");
        return;
    }

    // Đổi action sang update - Sửa lại phần này
    form.setAttribute('action', updateUrl); // Dùng setAttribute thay vì gán trực tiếp

    // Đảm bảo có method PUT
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement("input");
        methodInput.type = "hidden";
        methodInput.name = "_method";
        methodInput.value = "PUT";
        form.appendChild(methodInput);
    }

    // Helper: convert DB datetime -> datetime-local input
    function toDatetimeLocal(str) {
        if (!str) return '';
        let d = new Date(str.replace(' ', 'T'));
        if (isNaN(d.getTime())) d = new Date(str);
        if (isNaN(d.getTime())) return '';
        const pad = n => String(n).padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    // Đổ dữ liệu vào form
    document.getElementById("f-name").value          = promotion.name || "";
    document.getElementById("f-code").value          = promotion.code || "";
    document.getElementById("f-type").value          = promotion.type || "percent";
    document.getElementById("f-value").value         = promotion.value || "";
    document.getElementById("f-max-discount").value  = promotion.max_discount ?? "";
    document.getElementById("f-min-order").value     = promotion.min_order_value ?? promotion.min_order ?? "";
    document.getElementById("f-usage-limit").value   = promotion.usage_limit ?? "";
    document.getElementById("f-per-user-limit").value= promotion.per_user_limit ?? "";
    document.getElementById("f-start").value         = toDatetimeLocal(promotion.start_at ?? "");
    document.getElementById("f-end").value           = toDatetimeLocal(promotion.end_at ?? "");
    document.getElementById("f-active").checked      = !!promotion.is_active;

    // Đổi tiêu đề panel-left
    const title = document.getElementById("form-title-left");
    if (title) {
        title.textContent = "Sửa khuyến mãi";
    }

    // Nếu panel-left đang ẩn thì mở
    const panelLeft = document.querySelector(".panel-left");
    if (panelLeft) {
        panelLeft.style.display = "block";
        panelLeft.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Đổi nút thành "Cập nhật"
    const btnSubmit = form.querySelector('button[type="submit"], .btn-primary');
    if (btnSubmit) btnSubmit.textContent = "Cập nhật";
};

  // Gắn event validate
  if (form) {
    if (fType) fType.addEventListener('change', enableMaxDiscountIfPercent);

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

    enableMaxDiscountIfPercent();
  }
})();