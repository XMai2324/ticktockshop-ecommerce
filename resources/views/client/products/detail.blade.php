{{-- resources/views/product/detail.blade.php --}}
@extends('client.home')

@section('title')
  {{ $product->name }} - TickTock Shop
@endsection

@php
    use Illuminate\Support\Str;

    // ===== Folder ảnh theo danh mục =====
    $folder = 'Watch/Watch_nu';
    $catSlug = Str::slug(optional($product->category)->name ?? '');
    if ($catSlug === 'nam') {
        $folder = 'Watch/Watch_nam';
    } elseif ($catSlug === 'cap-doi') {
        $folder = 'Watch/Watch_cap';
    }

    // Ảnh chính (fallback nếu trống)
    $imageUrl = $product->image
        ? asset('storage/' . $folder . '/' . $product->image)
        : asset('storage/Watch/Watch_nu/Casio1.1.jpg');

    // Nếu bạn có cột images (array/json/csv) và muốn hiển thị thêm:
    $toArray = function ($raw) {
        if (!$raw) {
            return [];
        }
        if (is_array($raw)) {
            return array_values(array_filter($raw, fn($v) => is_string($v) && trim($v) != ''));
        }
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter($decoded, fn($v) => is_string($v) && trim($v) != ''));
        }
        return array_values(
            array_filter(preg_split('/\s*[,;|]\s*/', (string) $raw) ?: [], fn($v) => is_string($v) && trim($v) != ''),
        );
    };
    $fileNames = $toArray($product->images ?? null);

    $thumbUrls = collect($fileNames)
        ->map(function ($img) use ($folder) {
            $img = ltrim($img, '/');
            if (preg_match('~^https?://~i', $img)) {
                return $img;
            } // URL tuyệt đối
            return asset('storage/' . $folder . '/' . $img); // tên file -> ghép folder
        })
        ->unique()
        ->reject(fn($u) => $u === $imageUrl);
@endphp

@section('content')
    <main>
        <section class="product-detail-page">
            <div class="container">

                {{-- Breadcrumbs --}}
                <div class="product-page-top row">
                    <p><a href="{{ route('home') }}">Trang chủ</a></p> <span>&#10230;</span>
                    @if ($product->category)
                        <p>
                            <a href="{{ route('products.byCategory', Str::slug($product->category->name)) }}">
                                {{ $product->category->name }}
                            </a>
                        </p>
                        <span>&#10230;</span>
                    @endif
                    <p>{{ $product->name }}</p>
                </div>

                <div class="product-detail-wrap row">
                    {{-- LEFT: Gallery --}}
                    <div class="product-detail-left">
                        <div class="product-gallery" id="gallery">
                            {{-- Ảnh chính + nút điều hướng --}}
                            <div class="main-image">
                                <button class="gallery-prev">&#10094;</button> {{-- nút trái ‹ --}}
                                <img id="mainImage" src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                <button class="gallery-next">&#10095;</button> {{-- nút phải › --}}
                            </div>

                            {{-- Thumbnails --}}
                            <div class="thumbs-wrap">
                                <div class="thumbs">
                                    {{-- thumb cho ảnh chính --}}
                                    <div class="thumb active">
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                                    </div>

                                    {{-- thumb cho ảnh phụ nếu có (từ cột images) --}}
                                    @foreach ($thumbUrls as $u)
                                        <div class="thumb">
                                            <img src="{{ $u }}" alt="{{ $product->name }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: Info --}}
                    <div class="product-detail-right">
                        <h1 class="product-title">{{ $product->name }}</h1>

                        <p class="product-price">
                            {{ number_format($product->price, 0, ',', '.') }}<sup>đ</sup>
                        </p>

                        <div class="product-meta">
                            <p><strong>Thương hiệu:</strong> {{ optional($product->brand)->name ?? 'Không rõ' }}</p>
                            <p><strong>Danh mục:</strong> {{ optional($product->category)->name ?? 'Không rõ' }}</p>
                        </div>

                        <p class="product-short-desc">{{ $product->description ?? 'Không có mô tả' }}</p>

                        {{-- Thêm vào giỏ hàng (khớp CartController: id + type + quantity) --}}
                        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="type" value="product">
                            <div class="quantity-box">
                                <label for="quantity">Số lượng:</label>
                                <input type="number" id="quantity" name="quantity" min="1" value="1"
                                    inputmode="numeric">
                            </div>
                            <button type="submit" class="btn-add-to-cart">
                                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                            </button>
                        </form>

                        {{-- Chi tiết / Đổi trả / Bảo hành --}}
                        <div class="product-accordions">
                            <details open>
                                <summary>Mô tả chi tiết</summary>
                                <div class="acc-content">
                                    {!! nl2br(e($product->long_description ?? ($product->description ?? 'Đang cập nhật...'))) !!}
                                </div>

                                {{-- THÔNG SỐ KỸ THUẬT --}}
                                @if ($product->movement || $product->case_material || $product->strap_material || $product->glass_material || $product->diameter || $product->water_resistance)
                                    <h4 style="margin-top:20px; font-weight:600;">Thông số kỹ thuật</h4>
                                    <table class="table table-bordered product-specs" style="width:100%; margin-top:10px;">
                                        <tbody>
                                            @if ($product->movement)
                                                <tr>
                                                    <td><strong>Bộ máy</strong></td>
                                                    <td>{{ $product->movement }}</td>
                                                </tr>
                                            @endif

                                            @if ($product->case_material)
                                                <tr>
                                                    <td><strong>Chất liệu vỏ</strong></td>
                                                    <td>{{ $product->case_material }}</td>
                                                </tr>
                                            @endif

                                            @if ($product->strap_material)
                                                <tr>
                                                    <td><strong>Chất liệu dây</strong></td>
                                                    <td>{{ $product->strap_material }}</td>
                                                </tr>
                                            @endif

                                            @if ($product->glass_material)
                                                <tr>
                                                    <td><strong>Chất liệu kính</strong></td>
                                                    <td>{{ $product->glass_material }}</td>
                                                </tr>
                                            @endif

                                            @if ($product->diameter)
                                                <tr>
                                                    <td><strong>Kích thước</strong></td>
                                                    <td>{{ $product->diameter }}</td>
                                                </tr>
                                            @endif

                                            @if ($product->water_resistance)
                                                <tr>
                                                    <td><strong>Chống nước</strong></td>
                                                    <td>{{ $product->water_resistance }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @endif
                            </details>
                            <details>
                                <summary>Chính sách đổi trả</summary>
                                <div class="acc-content">
                                    Đổi trong 7 ngày nếu lỗi do NSX. Giữ nguyên tem & phụ kiện.
                                </div>
                            </details>
                            <details>
                                <summary>Thông tin bảo hành</summary>
                                <div class="acc-content">
                                    Bảo hành 12 tháng chính hãng trên toàn quốc.
                                </div>
                            </details>
                            <div class="product-rating-summary">
                                <div class="rating-in_detail_product">
                                    <span class="avg-rating">{{ number_format($product->avg_rating, 1) }}</span>
                                    <span class="stars">
                                        {!! str_repeat('★', 1) !!}
                                    </span>
                                    <span class="rating-text" id="btn-show-reviews">
                                        Đánh giá sản phẩm ({{ $product->ratings->count() }})  >
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sản phẩm liên quan --}}
                @isset($related)
                    @if ($related->count())
                        <div class="related-products">
                            <h2 class="related-title">Sản phẩm liên quan</h2>
                            <div class="product-page-right-content">
                                @foreach ($related as $r)
                                    @php
                                        $rf = 'Watch/Watch_nu';
                                        $rCat = Str::slug(optional($r->category)->name ?? '');
                                        if ($rCat === 'nam') {
                                            $rf = 'Watch/Watch_nam';
                                        } elseif ($rCat === 'cap-doi') {
                                            $rf = 'Watch/Watch_cap';
                                        }
                                    @endphp
                                    <div class="product-page-right-content-item">
                                        <a href="{{ route('product.detail', ['product' => $r->slug]) }}" class="product-card">
                                            <img src="{{ asset('storage/' . $rf . '/' . $r->image) }}"
                                                alt="{{ $r->name }}">
                                            <h2 class="product-name">{{ $r->name }}</h2>
                                            <p>{{ number_format($r->price, 0, ',', '.') }}<sup>đ</sup></p>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endisset
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    {{-- CSS + JS khác --}}
    <link rel="stylesheet" href="{{ asset('css/client/review_rating.css') }}">
    <script src="{{ asset('js/client/review_rating.js') }}" defer></script>
    <script src="{{ asset('js/client/quickview.js') }}" defer></script>

    <script>
        // Cập nhật tất cả badge đếm giỏ (hỗ trợ #cart-count và .js-cart-count)
        function updateCartBadges(n) {
            document.querySelectorAll('.js-cart-count, #cart-count').forEach(el => {
                el.textContent = n;
                el.setAttribute('data-count', n);
            });
        }

        document.addEventListener('submit', async (e) => {
            const form = e.target.closest('.add-to-cart-form');
            if (!form) return;

            e.preventDefault();

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: new FormData(form)
                });

                const data = await res.json();

                if (data.success) {
                    alert('Đã thêm vào giỏ hàng!');
                    if ('cart_count' in data) {
                        updateCartBadges(data.cart_count);
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại!');
                }
            } catch (err) {
                console.error(err);
                alert('Không thể kết nối máy chủ.');
            }
        });
    </script>
@endsection

@include('client.products.review_rating')