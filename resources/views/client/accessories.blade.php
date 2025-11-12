@extends('client.home')

@section('title', 'Phụ kiện đồng hồ - TickTock Shop')

@php
  use Illuminate\Support\Str;

  // Map type -> folder, nhãn hiển thị, ảnh mặc định
  $TYPE_MAP = [
    'straps'  => ['folder' => 'accessories/straps',  'label' => 'Dây đeo',        'default' => 'default.jpg'],
    'boxes'   => ['folder' => 'accessories/boxes',   'label' => 'Hộp đựng',       'default' => 'default.jpg'],
    'glasses' => ['folder' => 'accessories/glasses', 'label' => 'Kính cường lực', 'default' => 'default.jpg'],
  ];

  $isAll = ($type === 'all');

  // Lấy nhãn breadcrumb
  $breadcrumbLabel = $isAll
    ? 'Tất cả phụ kiện'
    : ($TYPE_MAP[$type]['label'] ?? Str::headline($type));

  // Build URL ảnh với fallback + hỗ trợ URL tuyệt đối
  $imgUrl = function ($item, $kind) use ($TYPE_MAP) {
      $img = $item->image ?? '';
      if (is_string($img) && preg_match('~^https?://~i', $img)) return $img;

      $t = $item->type ?? $kind;
      $conf = $TYPE_MAP[$t] ?? null;
      if (!$conf) return asset('storage/accessories/default.jpg');

      $file = $img ? ltrim($img, '/') : $conf['default'];
      return asset('storage/' . trim($conf['folder'], '/') . '/' . $file);
  };

  // In giá
  $priceText = fn($v) => number_format((float)($v ?? 0), 0, ',', '.') . '₫';

  // Nhãn loại phụ kiện
  $typeLabel = fn($t) => $TYPE_MAP[$t]['label'] ?? Str::headline($t ?? '');
@endphp

@section('content')
<section class="product-page">
  <div class="container">

    {{-- Breadcrumb --}}
    <div class="product-page-top row">
      <p><a href="{{ route('home') }}">Trang chủ</a></p> <span>&#10230;</span>
      <p>{{ $breadcrumbLabel }}</p>
    </div>

    {{-- Danh sách phụ kiện --}}
    <div class="row">

      @php $renderList = $items; @endphp

      @forelse($renderList as $acc)
        @php
          $accType = $acc->type ?? ($isAll ? null : $type);
          if (!$accType && !empty($acc->image)) {
              $path = strtolower($acc->image);
              $accType = str_contains($path, 'strap') ? 'straps'
                       : (str_contains($path, 'box') ? 'boxes'
                       : (str_contains($path, 'glass') ? 'glasses' : null));
          }
          $accType = $accType ?: 'straps';
          $thumb = $imgUrl($acc, $accType);
        @endphp

        <div class="col-3">
          <div class="product-card">
            <a href="#"
               class="accessory-quick-view"
               data-id="{{ $acc->id }}"
               data-type="{{ $accType }}"
               aria-label="Xem nhanh {{ $typeLabel($accType) }}: {{ $acc->name }}">
              <img
                src="{{ $thumb }}"
                alt="{{ $acc->name }}"
                class="product-image"
                loading="lazy"
                width="320"
                height="320">
              <h3 class="product-name">{{ $acc->name }}</h3>
              <p class="product-price">{{ $priceText($acc->price) }}</p>
              <p class="product-desc">{{ Str::limit($acc->description ?? '', 100) }}</p>
            </a>

            {{-- Form: Trái = số lượng | Phải = Thêm vào giỏ hàng --}}
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
              @csrf
              <input type="hidden" name="id" value="{{ $acc->id }}">
              {{-- PHẢI là straps|boxes|glasses theo CartController --}}
              <input type="hidden" name="type" value="{{ $accType }}">

              <div class="card-actions">
                <div class="qty-control" data-id="{{ $acc->id }}">
                  <button type="button" class="qty-btn minus" aria-label="Giảm số lượng">−</button>
                  <input
                    type="number"
                    name="quantity"
                    class="qty-input"
                    value="1"
                    min="1"
                    step="1"
                    inputmode="numeric"
                    aria-label="Số lượng">
                  <button type="button" class="qty-btn plus" aria-label="Tăng số lượng">＋</button>
                </div>

                <button type="submit" class="btn-add-to-cart">
                  <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                </button>
              </div>
            </form>
          </div>
        </div>
      @empty
        <p>Chưa có phụ kiện nào!</p>
      @endforelse
    </div>

    {{-- Phân trang (nếu $items là paginator) --}}
    @if(method_exists($items, 'links'))
      <div class="product-page-right-bottom row">
        <div class="product-page-right-bottom-items">
          <p>Hiển thị {{ $items->count() }} phụ kiện</p>
        </div>
        <div class="product-page-right-bottom-items pagination-wrapper">
          {{ $items->links() }}
        </div>
      </div>
    @endif

  </div>
</section>

{{-- Quick View Modal (accessible) --}}
<div id="quickViewModal"
     class="modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="quickViewTitle"
     style="display:none;">
  <div id="quick-view-body"></div>
</div>

{{-- Styles nhỏ cho layout số lượng + nút --}}
<style>
  .product-card .card-actions{
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; margin-top:.75rem;
  }
  .qty-control{
    display:inline-flex; align-items:center;
    border:1px solid #e9e9e9; border-radius:12px; overflow:hidden; background:#fff;
  }
  .qty-btn{ width:36px; height:38px; border:0; background:#f5f5f5; cursor:pointer; font-size:20px; line-height:1; }
  .qty-btn:active{ transform:scale(0.98); }
  .qty-input{ width:56px; height:38px; border:0; text-align:center; font-weight:600; outline:none; }
  .qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button{ -webkit-appearance:none; margin:0; }
  .qty-input{ -moz-appearance:textfield; }
  .btn-add-to-cart{
    padding:.6rem .9rem; border-radius:10px; border:0;
    background:#d8343a; color:#fff; font-weight:600; cursor:pointer; white-space:nowrap;
    margin-left:auto; /* đẩy nút sát bên phải */
  }
  .btn-add-to-cart:hover{ filter:brightness(0.96); }
  @media (max-width:576px){
    .product-card .card-actions{ flex-direction:column; align-items:stretch; gap:10px; }
    .btn-add-to-cart{ margin-left:0; }
  }
</style>

{{-- AJAX thêm vào giỏ để không mở trang JSON + cập nhật mọi badge đếm --}}
<script>
function updateCartBadges(n){
  document.querySelectorAll('.js-cart-count, #cart-count').forEach(el => {
    el.textContent = n;
  });
}


document.addEventListener('submit', async (e) => {
  const form = e.target.closest('.add-to-cart-form');
  if(!form) return;

  e.preventDefault();

  try {
    const res = await fetch(form.action, {
      method: 'POST',
      headers: {'X-Requested-With':'XMLHttpRequest'},
      body: new FormData(form)
    });
    const data = await res.json();

    if (data.success) {
      alert('Đã thêm vào giỏ hàng!');
      if ('cart_count' in data) updateCartBadges(data.cart_count);
    } else {
      alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại!');
    }
  } catch (err) {
    console.error(err);
    alert('Không thể kết nối máy chủ.');
  }
});

document.addEventListener('click', function(e){
  const btn = e.target.closest('.qty-btn');
  if(!btn) return;

  const wrap = btn.closest('.qty-control');
  const input = wrap?.querySelector('.qty-input');
  if(!input) return;

  const min = parseInt(input.getAttribute('min') || '1', 10);
  const step = parseInt(input.getAttribute('step') || '1', 10);
  let val = parseInt(input.value || '1', 10);

  if(btn.classList.contains('plus')) {
    val += step;
  } else if(btn.classList.contains('minus')) {
    val = Math.max(min, val - step);
  }
  input.value = isNaN(val) ? min : val;
});

document.addEventListener('change', function(e){
  if(!e.target.classList.contains('qty-input')) return;
  const input = e.target;
  const min = parseInt(input.getAttribute('min') || '1', 10);
  let val = parseInt(input.value || '1', 10);
  input.value = (!isNaN(val) && val >= min) ? val : min;
});
</script>
@endsection