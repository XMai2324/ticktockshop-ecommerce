@extends('client.home')

@section('title', 'Phụ kiện đồng hồ - TickTock Shop')

@section('content')
<div class="container">
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


        @elseif($type === 'glass')
            @forelse($items as $glass)
                <div class="col-3">
                    <div class="product-card">
                        <a href="#" class="accessory-quick-view" data-id="{{ $glass->id }}" data-type="glass">
                            <img src="{{ asset('storage/accessories/glass/' . $glass->image) }}" alt="{{ $glass->name }}" class="product-image">
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
</div>
<div id="quickViewModal" class="modal" style="display: none;">
        <div id="quick-view-body">
        </div>
</div>
<script src="{{ asset('js/client/quickview.js') }}"></script>
@endsection
