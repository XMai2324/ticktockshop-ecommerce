@extends('client.home')

@section('title', 'Phụ kiện đồng hồ - TickTock Shop')

@section('content')
<section class="product-page">
  <div class="container">

    {{-- Breadcrumb --}}
    <div class="product-page-top row">
    <p><a href="{{ route('home') }}">Trang chủ</a></p> <span>&#10230;</span>
    <p>
        @if($type === 'straps') Dây đeo
        @elseif($type === 'boxes') Hộp đựng
        @elseif($type === 'glasses') Kính cường lực
        @elseif($type === 'all') Tất cả phụ kiện
        @endif
    </p>
    </div>

    {{-- Danh sách phụ kiện --}}
    <div class="row">
      @if($type === 'straps')
        @forelse($items as $strap)
          <div class="col-3">
            <div class="product-card">
              <a href="#" class="accessory-quick-view" data-id="{{ $strap->id }}" data-type="straps">
                <img src="{{ asset('storage/accessories/straps/' . $strap->image) }}" alt="{{ $strap->name }}" class="product-image">
                <h3 class="product-name">{{ $strap->name }}</h3>
                <p class="product-price">{{ number_format($strap->price, 0, ',', '.') }}₫</p>
                <p class="product-desc">{{ $strap->description }}</p>
              </a>
            </div>
          </div>
        @empty
          <p>Chưa có dây đeo đồng hồ nào!</p>
        @endforelse

      @elseif($type === 'boxes')
        @forelse($items as $box)
          <div class="col-3">
            <div class="product-card">
              <a href="#" class="accessory-quick-view" data-id="{{ $box->id }}" data-type="boxes">
                <img src="{{ asset('storage/accessories/boxes/' . $box->image) }}" alt="{{ $box->name }}" class="product-image">
                <h3 class="product-name">{{ $box->name }}</h3>
                <p class="product-price">{{ number_format($box->price, 0, ',', '.') }}₫</p>
                <p class="product-desc">{{ $box->description }}</p>
              </a>
            </div>
          </div>
        @empty
          <p>Chưa có hộp đựng đồng hồ nào!</p>
        @endforelse

      @elseif($type === 'glasses')
        @forelse($items as $glass)
          <div class="col-3">
            <div class="product-card">
              <a href="#" class="accessory-quick-view" data-id="{{ $glass->id }}" data-type="glasses">
                <img src="{{ asset('storage/accessories/glasses/' . $glass->image) }}" alt="{{ $glass->name }}" class="product-image">
                <h3 class="product-name">{{ $glass->name }}</h3>
                <p class="product-price">{{ number_format($glass->price, 0, ',', '.') }}₫</p>
                <p class="product-desc">{{ $glass->description }}</p>
              </a>
            </div>
          </div>
        @empty
          <p>Chưa có kính cường lực nào!</p>
        @endforelse
      @endif
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

<div id="quickViewModal" class="modal" style="display: none;">
  <div id="quick-view-body"></div>
</div>
<script src="{{ asset('js/client/quickview.js') }}"></script>
@endsection