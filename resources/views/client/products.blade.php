@extends('client.home')

@section('title')
    @if(isset($currentBrand) && isset($currentCategory))
        {{ $currentBrand->name . ' ' . $currentCategory->name }} - TickTock Shop
    @else
        Sản phẩm - TickTock Shop
    @endif
@endsection

@section('content')
<section class="product-page">
        <div class="container">
            <div class="product-page-top row">
                <p><a href="{{ route('home') }}">Trang chủ</a></p> <span>&#10230;</span>
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
                {{-- @else
                    <p>Tất cả sản phẩm</p>
                @endif --}}
                @endif
            </div>


            <div class="product-page-right-top row">
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

                {{-- Dropdown: Khoảng giá --}}
                <div class="filter-group">
                @php
                    $url = fn($arr) => request()->fullUrlWithQuery($arr);
                    $pr  = request('price_range');
                @endphp

                <select class="filter-select select-box" onchange="location = this.value;">
                    <option value="{{ $url(['price_range'=>null]) }}" {{ $pr==null ? 'selected' : '' }}>-- Khoảng giá --</option>
                    <option value="{{ $url(['price_range'=>'0-1000000']) }}"           {{ $pr=='0-1000000' ? 'selected' : '' }}>Dưới 1 triệu</option>
                    <option value="{{ $url(['price_range'=>'1000000-3000000']) }}"     {{ $pr=='1000000-3000000' ? 'selected' : '' }}>1 - 3 triệu</option>
                    <option value="{{ $url(['price_range'=>'3000000-5000000']) }}"     {{ $pr=='3000000-5000000' ? 'selected' : '' }}>3 - 5 triệu</option>
                    <option value="{{ $url(['price_range'=>'5000000-7000000']) }}"     {{ $pr=='5000000-7000000' ? 'selected' : '' }}>5 - 7 triệu</option>
                    <option value="{{ $url(['price_range'=>'7000000-10000000']) }}"    {{ $pr=='7000000-10000000' ? 'selected' : '' }}>7 - 10 triệu</option>
                    <option value="{{ $url(['price_range'=>'10000000-30000000']) }}"   {{ $pr=='10000000-30000000' ? 'selected' : '' }}>10 - 30 triệu</option>
                    <option value="{{ $url(['price_range'=>'30000000-50000000']) }}"   {{ $pr=='30000000-50000000' ? 'selected' : '' }}>30 - 50 triệu</option>
                    <option value="{{ $url(['price_range'=>'50000000-100000000']) }}"  {{ $pr=='50000000-100000000' ? 'selected' : '' }}>50 - 100 triệu</option>
                    <option value="{{ $url(['price_range'=>'100000000-200000000']) }}" {{ $pr=='100000000-200000000' ? 'selected' : '' }}>100 - 200 triệu</option>
                    <option value="{{ $url(['price_range'=>'200000000-300000000']) }}" {{ $pr=='200000000-300000000' ? 'selected' : '' }}>200 - 300 triệu</option>
                    <option value="{{ $url(['price_range'=>'300000000-400000000']) }}" {{ $pr=='300000000-400000000' ? 'selected' : '' }}>300 - 400 triệu</option>
                    <option value="{{ $url(['price_range'=>'400000000-500000000']) }}" {{ $pr=='400000000-500000000' ? 'selected' : '' }}>400 - 500 triệu</option>
                    <option value="{{ $url(['price_range'=>'500000000-1000000000']) }}"{{ $pr=='500000000-1000000000' ? 'selected' : '' }}>Trên 500 triệu</option>
                </select>
                </div>

                {{-- Dropdown: Sắp xếp --}}
                <div class="product-page-right-top-item">
                    <select onchange="location = this.value;" class="select-box">
                        <option value="{{ request()->url() }}" {{ request('sort') == null ? 'selected' : '' }}>Sắp xếp</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'desc']) }}" {{ request('sort') == 'desc' ? 'selected' : '' }}>
                            Giá cao đến thấp
                        </option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'asc']) }}" {{ request('sort') == 'asc' ? 'selected' : '' }}>
                            Giá thấp đến cao
                        </option>
                    </select>
                </div>
                                {{-- Danh sách sản phẩm --}}
                <div class="product-page-right-content">
                    @forelse($products as $product)
                        <div class="product-page-right-content-item">
                            @php
                                $categoryFolder = 'Watch/Watch_nu'; // mặc định

                                if (isset($product->category)) {
                                    $slug = \Illuminate\Support\Str::slug($product->category->name);

                                    if ($slug === 'nam') {
                                        $categoryFolder = 'Watch/Watch_nam';
                                    } elseif ($slug === 'cap-doi') {
                                        $categoryFolder = 'Watch/Watch_cap';
                                    }
                                }
                            @endphp
                             <a href="javascript:void(0);" class="product-quick-view" data-slug="{{ $product->slug }}">
                            <img src="{{ asset('storage/' . $categoryFolder . '/' . $product->image) }}" alt="{{ $product->name }}">
                            <h1>{{ $product->name }}</h1>
                            <p>{{ number_format($product->price, 0, ',', '.') }}<sup>đ</sup></p>
                            </a>
                        </div>
                    @empty
                        <p class="no-product-message">Không có sản phẩm nào.</p>
                    @endforelse
                </div>

                {{-- Phân trang --}}
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
    </div>
</section>
    <!-- Quick View Modal -->
<div id="quickViewModal" class="modal" style="display: none;">
        <div id="quick-view-body">

        </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('js/client/app.js') }}" defer></script>
    <script src="{{ asset('js/client/quickview.js') }}" defer></script>
@endsection

@section('scripts')
    <script src="{{ asset('js/client/app.js') }}" defer></script>
    <script src="{{ asset('js/client/quickview.js') }}" defer></script>
@endsection