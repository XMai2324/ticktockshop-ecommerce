@extends('client.home')

@php
    use Illuminate\Support\Str;
@endphp

@section('title')
    @if(isset($currentBrand) && isset($currentCategory))
        {{ $currentBrand->name . ' ' . $currentCategory->name }} - TickTock Shop
    @else
        Sản phẩm - TickTock Shop
    @endif
@endsection

@section('content')
<main>
<section class="product-page">
    <div class="container">
        {{-- ==== Breadcrumb ==== --}}
        <div class="product-page-top row">
            <p><a href="{{ route('home') }}">Trang chủ</a></p> 
            <span>&#10230;</span>

            @if(isset($keyword) && $keyword)
                <p>{{ $keyword }}</p>
            @elseif(isset($currentBrand) && isset($currentCategory))
                <p>
                    <a href="{{ route('products.filter', [
                        'category' => Str::slug($currentCategory->name),
                        'brand' => Str::slug($currentBrand->name)
                    ]) }}">
                        {{ $currentBrand->name }} {{ strtolower($currentCategory->name) }}
                    </a>
                </p>
            @elseif(isset($currentBrand))
                <p><a href="{{ route('products.filter', ['slug' => $currentBrand->slug]) }}">{{ $currentBrand->name }}</a></p>
            @elseif(isset($currentCategory))
                <p>
                    <a href="{{ route('products.byCategory', Str::slug($currentCategory->name)) }}">
                        {{ $currentCategory->name }}
                    </a>
                </p>
            @endif
        </div>

        <div class="product-page-right-top row">
            {{-- ==== Tiêu đề danh sách ==== --}}
            <div class="product-page-right-top-item">
                <p class="product-page-title">
                    @if(isset($currentBrand) && isset($currentCategory))
                        {{ strtoupper($currentBrand->name) }} - {{ strtoupper($currentCategory->name) }}
                    @elseif(isset($currentBrand))
                        SẢN PHẨM THƯƠNG HIỆU: {{ strtoupper($currentBrand->name) }}
                    @elseif(isset($currentCategory))
                        SẢN PHẨM LOẠI: {{ strtoupper($currentCategory->name) }}
                    @else
                        TẤT CẢ SẢN PHẨM
                    @endif
                </p>
            </div>  

            {{-- ==== Dropdown: Khoảng giá ==== --}}
            <div class="filter-group">
                @php
                    $pr  = request('price_range');
                @endphp
                <select class="filter-select select-box" name="price_range">
                    <option value="" {{ $pr==null ? 'selected' : '' }}>-- Khoảng giá --</option>
                    <option value="0-1000000"           {{ $pr=='0-1000000' ? 'selected' : '' }}>Dưới 1 triệu</option>
                    <option value="1000000-3000000"     {{ $pr=='1000000-3000000' ? 'selected' : '' }}>1 - 3 triệu</option>
                    <option value="3000000-5000000"     {{ $pr=='3000000-5000000' ? 'selected' : '' }}>3 - 5 triệu</option>
                    <option value="5000000-7000000"     {{ $pr=='5000000-7000000' ? 'selected' : '' }}>5 - 7 triệu</option>
                    <option value="7000000-10000000"    {{ $pr=='7000000-10000000' ? 'selected' : '' }}>7 - 10 triệu</option>
                    <option value="10000000-30000000"   {{ $pr=='10000000-30000000' ? 'selected' : '' }}>10 - 30 triệu</option>
                    <option value="30000000-50000000"   {{ $pr=='30000000-50000000' ? 'selected' : '' }}>30 - 50 triệu</option>
                    <option value="50000000-100000000"  {{ $pr=='50000000-100000000' ? 'selected' : '' }}>50 - 100 triệu</option>
                    <option value="100000000-200000000" {{ $pr=='100000000-200000000' ? 'selected' : '' }}>100 - 200 triệu</option>
                    <option value="200000000-300000000" {{ $pr=='200000000-300000000' ? 'selected' : '' }}>200 - 300 triệu</option>
                    <option value="300000000-400000000" {{ $pr=='300000000-400000000' ? 'selected' : '' }}>300 - 400 triệu</option>
                    <option value="400000000-500000000" {{ $pr=='400000000-500000000' ? 'selected' : '' }}>400 - 500 triệu</option>
                    <option value="500000000-1000000000"{{ $pr=='500000000-1000000000' ? 'selected' : '' }}>Trên 500 triệu</option>
                </select>
            </div>

            {{-- ==== Dropdown: Sắp xếp ==== --}}
            <div class="product-page-right-top-item">
                <select class="select-box" name="sort">
                    <option value="" {{ request('sort') == null ? 'selected' : '' }}>Sắp xếp</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
                    <option value="asc"  {{ request('sort') == 'asc'  ? 'selected' : '' }}>Giá thấp đến cao</option>
                </select>
            </div>

            {{-- ==== Danh sách sản phẩm ==== --}}
            <div class="product-page-right-content">
                @forelse($products as $product)
                    @php
                        $categoryFolder = 'Watch/Watch_nu';
                        if ($product->category) {
                            $slug = Str::slug($product->category->name);
                            if ($slug === 'nam') {
                                $categoryFolder = 'Watch/Watch_nam';
                            } elseif ($slug === 'cap-doi') {
                                $categoryFolder = 'Watch/Watch_cap';
                            }
                        }
                    @endphp

                    <div class="product-page-right-content-item">
                        {{-- Liên kết sang trang chi tiết --}}
                        <a href="{{ route('product.detail', ['product' => $product->slug]) }}" class="product-card">
                            <img loading="lazy" src="{{ asset('storage/' . $categoryFolder . '/' . $product->image) }}" alt="{{ $product->name }}">
                            <h2>{{ $product->name }}</h2>
                            <p>{{ number_format($product->price, 0, ',', '.') }}<sup>đ</sup></p>
                        </a>
                    </div>
                @empty
                    <p class="no-product-message">Không có sản phẩm nào.</p>
                @endforelse
            </div>

            {{-- ==== Phân trang ==== --}}
            <div class="product-page-right-bottom row">
                <div class="product-page-right-bottom-items">
                    <p>Hiển thị {{ $products->count() }} sản phẩm</p>
                </div>
                <div class="product-page-right-bottom-items pagination-wrapper">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

@section('scripts')
<script src="{{ asset('js/client/quickview.js') }}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const priceSelect = document.querySelector('select[name="price_range"]');
    const sortSelect  = document.querySelector('select[name="sort"]');

    function updateQuery(param, value) {
        const url = new URL(window.location.href);
        if (value) url.searchParams.set(param, value);
        else url.searchParams.delete(param);
        url.searchParams.delete('page'); // reset về trang đầu tiên khi lọc/sắp xếp
        window.location.href = url.toString();
    }

    if (priceSelect) {
        priceSelect.addEventListener('change', (e) => updateQuery('price_range', e.target.value));
    }
    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => updateQuery('sort', e.target.value));
    }
});
</script>
@endsection