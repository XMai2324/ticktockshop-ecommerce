document.addEventListener('DOMContentLoaded', () => {
  // Nếu layout đã có <main>, KHÔNG cần <main> lồng bên trong products.blade.php
  const mainContainer = document.querySelector('main');
  if (!mainContainer) return;

  let inflight; // AbortController cho request đang chạy

  async function ajaxGoto(url, push = true) {
    try {
      // Hủy request trước (nếu có) để tránh race condition khi thao tác nhanh
      if (inflight) inflight.abort();
      inflight = new AbortController();

      const y = window.scrollY; // nhớ vị trí cuộn hiện tại
      const resp = await fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        signal: inflight.signal
      });
      const html = await resp.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newMain = doc.querySelector('main');
      if (!newMain) throw new Error('No <main> found in response');

      mainContainer.innerHTML = newMain.innerHTML; // thay nội dung
      if (push) history.pushState({}, '', url);
      window.scrollTo({ top: y }); // giữ nguyên vị trí cuộn
    } catch (e) {
      if (e.name === 'AbortError') return; // bị hủy do thao tác nhanh -> bỏ qua
      // fallback: chuyển trang bình thường khi có lỗi
      window.location.href = url;
    } finally {
      inflight = null;
    }
  }

  // =========================
  // Event delegation: FILTERS
  // =========================
  document.addEventListener('change', (e) => {
    const sel = e.target;
    if (!sel || sel.tagName !== 'SELECT') return;

    // sort
    if (sel.name === 'sort') {
      const url = new URL(window.location.href);
      const val = sel.value;
      if (val === 'asc' || val === 'desc') url.searchParams.set('sort', val);
      else url.searchParams.delete('sort');

      // đổi filter thì reset về page=1
      url.searchParams.delete('page');
      ajaxGoto(url.toString());
    }

    // price_range
    if (sel.name === 'price_range') {
      const url = new URL(window.location.href);
      const val = sel.value;
      if (val) url.searchParams.set('price_range', val);
      else url.searchParams.delete('price_range');

      // đổi filter thì reset về page=1
      url.searchParams.delete('page');
      ajaxGoto(url.toString());
    }
  });

  // ===========================
  // Event delegation: PAGINATION
  // ===========================
  document.addEventListener('click', (e) => {
    // Bắt click vào link phân trang bên trong .pagination-wrapper
    const a = e.target.closest('.pagination-wrapper a');
    if (!a) return;
    // Cho phép mở tab mới nếu user bấm Ctrl/Cmd/chuột giữa
    const openInNew = e.metaKey || e.ctrlKey || e.shiftKey || e.button === 1;
    if (openInNew) return;

    e.preventDefault();
    ajaxGoto(a.href);
  });

  // ===========================
  // Back/Forward của trình duyệt
  // ===========================
  window.addEventListener('popstate', () => {
    ajaxGoto(location.href, false);
  });
});
