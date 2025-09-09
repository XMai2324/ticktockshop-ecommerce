@extends('client.home')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<div class="container">
    <h2>Kết quả tìm kiếm cho từ khóa: <strong>{{ $keyword }}</strong></h2>

    {{-- Đồng hồ --}}
    <h3>Đồng hồ</h3>
    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-item">
                <img src="{{ asset('storage/Watch/' . Str::slug($product->category->name ?? 'nu') . '/' . $product->image) }}" alt="{{ $product->name }}">
                <p>{{ $product->name }}</p>
                <p>{{ number_format($product->price) }}đ</p>
            </div>
        @empty
            <p>Không tìm thấy đồng hồ nào.</p>
        @endforelse
    </div>

    {{-- Dây đeo --}}
    <h3>Dây đeo</h3>
    <div class="product-grid">
        @forelse($straps as $strap)
            <div class="product-item">
                <img src="{{ asset('storage/accessories/watch_strap/' . $strap->image) }}" alt="{{ $strap->name }}">
                <p>{{ $strap->name }}</p>
                <p>{{ number_format($strap->price) }}đ</p>
            </div>
        @empty
            <p>Không có dây đeo phù hợp.</p>
        @endforelse
    </div>

    {{-- Hộp đựng --}}
    <h3>Hộp đựng</h3>
    <div class="product-grid">
        @forelse($boxes as $box)
            <div class="product-item">
                <img src="{{ asset('storage/accessories/box/' . $box->image) }}" alt="{{ $box->name }}">
                <p>{{ $box->name }}</p>
                <p>{{ number_format($box->price) }}đ</p>
            </div>
        @empty
            <p>Không có hộp đựng phù hợp.</p>
        @endforelse
    </div>

    {{-- Kính cường lực --}}
    <h3>Kính cường lực</h3>
    <div class="product-grid">
        @forelse($glasses as $glass)
            <div class="product-item">
                <img src="{{ asset('storage/accessories/glass/' . $glass->image) }}" alt="{{ $glass->name }}">
                <p>{{ $glass->name }}</p>
                <p>{{ number_format($glass->price) }}đ</p>
            </div>
        @empty
            <p>Không có kính phù hợp.</p>
        @endforelse
    </div>
</div>
@endsection
