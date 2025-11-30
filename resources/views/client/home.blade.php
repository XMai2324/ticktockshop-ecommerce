<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TickTock Shop')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/client/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/accessories.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/warranty.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/cart.css') }}">

    @if (session('login_error') || session('error') || $errors->any())
    <meta name="login-error" content="1">
    @endif

    @if (session('register_error'))
    <meta name="register-error" content="1">
    @endif

    @if (session('open_forgot'))
    <meta name="open-forgot" content="1">
    @endif

    @if (session('forgot_error'))
    <meta name="forgot-error" content="1">
    @endif

    <style>
        .user-menu {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.dropdown-menu-user {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    min-width: 160px;
    list-style: none;
    padding: 5px 0;
    z-index: 999;
}

.dropdown-menu-user li {
    padding: 10px;
}

.dropdown-menu-user li a {
    text-decoration: none;
    color: #333;
}

.dropdown-menu-user li:hover {
    background: #f5f5f5;
}

.user-menu:hover .dropdown-menu-user {
    display: block;
}

</style>


</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('storage/logo2.png')}}" alt="logoShop">
        </div>

    <div class="header_menu">
            <li>
                <a href="{{ route('home') }}">THƯƠNG HIỆU</a>
                <ul class="sub_TH">
                    <li><a href="{{ route('products.filter', ['brand' => 'casio']) }}">Casio</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'rolex']) }}">Rolex</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'citizen']) }}">Citizen</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'rado']) }}">Rado</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'seiko']) }}">Seiko</a></li>
                </ul>
            </li>

            {{-- NỮ: click vào tiêu đề sẽ ra tất cả sản phẩm Nữ --}}
            <li>
                <a href="{{ route('products.byCategory', 'nu') }}">NỮ</a>
                <ul class="sub_Nu">
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'casio']) }}">Casio nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'rolex']) }}">Rolex nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'citizen']) }}">Citizen nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'rado']) }}">Rado nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'seiko']) }}">Seiko nữ</a></li>
                </ul>
            </li>

            {{-- NAM --}}
            <li>
                <a href="{{ route('products.byCategory', 'nam') }}">NAM</a>
                <ul class="sub_Nam">
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'casio']) }}">Casio nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'rolex']) }}">Rolex nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'citizen']) }}">Citizen nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'rado']) }}">Rado nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'seiko']) }}">Seiko nam</a></li>
                </ul>
            </li>

            {{-- CẶP ĐÔI --}}
            <li>
                <a href="{{ route('products.byCategory', 'cap-doi') }}">CẶP ĐÔI</a>
                <ul class="sub_Doi">
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'casio']) }}">Casio đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'rolex']) }}">Rolex đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'rado']) }}">Rado đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'citizen']) }}">Citizen đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'seiko']) }}">Seiko đôi</a></li>
                </ul>
            </li>

            {{-- PHỤ KIỆN --}}
            <li>
                <a href="">PHỤ KIỆN</a>
                <ul class="sub_pk">
                    <li><a href="{{ route('accessories.straps') }}">Dây đeo</a></li>
                    <li><a href="{{ route('accessories.boxes') }}">Hộp đựng</a></li>
                    <li><a href="{{ route('accessories.glasses') }}">Kính cường lực</a></li>
                </ul>
            </li>

            <li><a href="{{ route('warranty.form') }}">THÔNG TIN BẢO HÀNH</a></li>
        </div>

    <div class="header_other">
        <li class="search-wrapper">
            <form id="searchForm" action="{{ route('products.filter') }}" method="GET" class="search-form">
                <input id="searchInput" name="keyword" placeholder="Tìm kiếm" type="text" autocomplete="off" value="{{ request('keyword') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <div class="search-history" id="searchHistory">
                <h3 class="search-heading">Lịch sử tìm kiếm</h3>
                <ul class="search-history-list"></ul>
            </div>
        </li>

        <li class="header-user">
            <div class="user-menu">
                <i class="fa fa-user"></i>

                @auth
                    <span class="user-name">{{ Auth::user()->name }}</span>

                    <ul class="dropdown-menu-user">
                        <li><a href="{{ route('profile') }}">Hồ sơ</a></li>
                        <li><a href="{{ route('orders.history') }}">Lịch sử đơn hàng</a></li>

                    </ul>
                @else
                    <a title="Đăng nhập" id="login-icon" href="javascript:void(0);">Đăng nhập</a>
                @endauth
            </div>
        </li>


        <div class="overlay" id="login-overlay">
            @include('client.auth.login')
            @include('client.auth.register')
        </div>

        @php
            $cartItems = session('cart') ?? [];
            $totalQty  = array_sum(array_column($cartItems, 'quantity'));
        @endphp

        <a href="{{ route('cart.index') }}" class="cart-icon" aria-label="Giỏ hàng">
            <i class="fa fa-shopping-bag"></i>
            {{-- Luôn render badge để JS có thể cập nhật ngay sau khi thêm vào giỏ --}}
            <span
                class="cart-count js-cart-count"
                id="cart-count"
                aria-live="polite"
                aria-atomic="true"
                data-count="{{ $totalQty }}">{{ $totalQty }}
            </span>
        </a>

        @auth
            <li class="logout-item">
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button type="submit" class="logout-btn"><i class="fa fa-sign-out-alt"></i></button>
                </form>
            </li>
        @endauth
    </div>
</header>
    <section id="slide">
        <div class="aspect-ratio-169">
            <img src="{{ asset('storage/slide1.jpg')}}" alt="">
            <img src="{{ asset('storage/slide2.jpg')}}" alt="">
            <img src="{{ asset('storage/slide3.png')}}" alt="">
            <img src="{{ asset('storage/slide4.png')}}" alt="">
            <img src="{{ asset('storage/slide5.jpg')}}" alt="">

        </div>
        <div class="dot-slide">
            <div class="dot active"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>


        </div>
    </section>


   <main style="margin-top: 100px">
    {{-- Chỉ home (hoặc những trang truyền biến này) mới hiện 2 section --}}
    @isset($bestSellers)
        <!-- SẢN PHẨM BÁN CHẠY -->
        <section class="home-section">
            <h2 class="home-section-title title-premium">SẢN PHẨM BÁN CHẠY</h2>
            <div class="slider-container">
                {{-- Mũi tên bên trái --}}
                <button class="slide-arrow left" data-target="bestseller-slider">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div class="slider-wrapper">
                    <div class="product-slider" id="bestseller-slider">
                        @forelse ($bestSellers as $product)
                            <div class="product-card">
                                <a href="{{ route('product.detail', $product->slug) }}" class="product-image-wrap">
                                    @php
                                        $folder = 'Watch/';

                                        if ($product->category->name === 'Nữ') {
                                            $folder .= 'Watch_nu/';
                                        } elseif ($product->category->name === 'Nam') {
                                            $folder .= 'Watch_nam/';
                                        } elseif ($product->category->name === 'Cặp đôi') {
                                            $folder .= 'Watch_cap/';
                                        }
                                    @endphp
                                    <img src="{{ asset('storage/' . $folder . $product->image) }}"
                                        alt="{{ $product->name }}">
                                </a>
                                <div class="product-info">
                                    <h3 class="product-name">
                                        <a href="{{ route('product.detail', $product->slug) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <p class="product-price">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </p>

                                    {{-- RATING: dùng dữ liệu giống popup --}}
                                    @php
                                        $avgRating = round($product->ratings->avg('rating') ?? 0, 1);
                                        $fullStars = floor($avgRating);
                                        $hasHalf   = ($avgRating - $fullStars) >= 0.5;
                                    @endphp

                                    <div class="product-rating">
                                        {{-- sao đầy --}}
                                        @for ($i = 1; $i <= $fullStars; $i++)
                                            <i class="fa-solid fa-star star-full"></i>
                                        @endfor

                                        {{-- nửa sao --}}
                                        @if ($hasHalf)
                                            <i class="fa-solid fa-star-half-stroke star-full"></i>
                                        @endif

                                        {{-- sao trống còn lại --}}
                                        @for ($i = 1; $i <= (5 - $fullStars - ($hasHalf ? 1 : 0)); $i++)
                                            <i class="fa-regular fa-star star-empty"></i>
                                        @endfor

                                        <span class="rating-text">{{ $avgRating }}/5</span>
                                    </div>
                                    <div class="product-actions">
                                        <a href="{{ route('product.detail', $product->slug) }}" class="btn-primary btn-view-product">
                                            Xem sản phẩm
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Hiện chưa có sản phẩm bán chạy.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Mũi tên bên phải --}}
                <button class="slide-arrow right" data-target="bestseller-slider">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </section>

        <!-- SẢN PHẨM MỚI -->
        <section class="home-section">
            <h2 class="home-section-title title-premium">SẢN PHẨM MỚI</h2>
            <div class="slider-container">
                {{-- Mũi tên bên trái --}}
                <button class="slide-arrow left" data-target="new-slider">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div class="slider-wrapper">
                    <div class="product-slider" id="new-slider">
                        @forelse ($newProducts as $product)
                            <div class="product-card">
                                <a href="{{ route('product.detail', $product->slug) }}" class="product-image-wrap">
                                    @php
                                        $folder = 'Watch/';

                                        if ($product->category->name === 'Nữ') {
                                            $folder .= 'Watch_nu/';
                                        } elseif ($product->category->name === 'Nam') {
                                            $folder .= 'Watch_nam/';
                                        } elseif ($product->category->name === 'Cặp đôi') {
                                            $folder .= 'Watch_cap/';
                                        }
                                    @endphp

                                    <img src="{{ asset('storage/' . $folder . $product->image) }}"
                                        alt="{{ $product->name }}">
                                </a>
                                <div class="product-info">
                                    <h3 class="product-name">
                                        <a href="{{ route('product.detail', $product->slug) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>

                                    <p class="product-price">
                                        {{ number_format($product->price, 0, ',', '.') }}₫
                                    </p>

                                    {{-- RATING: dùng dữ liệu giống popup --}}
                                    @php
                                        $avgRating = round($product->ratings->avg('rating') ?? 0, 1);
                                        $fullStars = floor($avgRating);
                                        $hasHalf   = ($avgRating - $fullStars) >= 0.5;
                                    @endphp

                                    <div class="product-rating">
                                        {{-- sao đầy --}}
                                        @for ($i = 1; $i <= $fullStars; $i++)
                                            <i class="fa-solid fa-star star-full"></i>
                                        @endfor

                                        {{-- nửa sao --}}
                                        @if ($hasHalf)
                                            <i class="fa-solid fa-star-half-stroke star-full"></i>
                                        @endif

                                        {{-- sao trống còn lại --}}
                                        @for ($i = 1; $i <= (5 - $fullStars - ($hasHalf ? 1 : 0)); $i++)
                                            <i class="fa-regular fa-star star-empty"></i>
                                        @endfor

                                        <span class="rating-text">{{ $avgRating }}/5</span>
                                    </div>
                                    <div class="product-actions">
                                        <a href="{{ route('product.detail', $product->slug) }}" class="btn-primary btn-view-product">
                                            Xem sản phẩm
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Hiện chưa có sản phẩm mới.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Mũi tên bên phải --}}
                <button class="slide-arrow right" data-target="new-slider">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </section>
    @endisset

    {{-- Nội dung các page khác vẫn dùng layout này --}}
    @yield('content')
    </main>

    <section class="footer">
        <div class="left_footer">
            <div class="footer-logo">
                <img src="{{ asset('storage/logo2.png')}}" alt="footer-logo">
            </div>
            <p><strong>TickTock Shop</strong> là hệ thống cửa hàng đồng hồ chính hãng tại Việt Nam,
                chuyên cung cấp các thương hiệu nổi tiếng thế giới như Casio, Seiko, Citizen,
                Rolex, Rado cùng nhiều phụ kiện đi kèm (dây đeo, hộp đựng, kính cường lực).
            </p>
            <p>
                Với phương châm <em>“Uy tín tạo niềm tin”</em>, TickTock Shop luôn cam kết
                sản phẩm 100% chính hãng, bảo hành chính hãng toàn quốc, dịch vụ giao hàng nhanh chóng
                và đội ngũ tư vấn nhiệt tình để mang đến trải nghiệm mua sắm hoàn hảo nhất cho khách hàng.
            </p>
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <h4 >Kết nối với chúng tôi</h4>
                <div class="social-links" >
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>
        
        <div class="right_footer">
            <h4>Liên hệ</h4>
            <p><i class="fa-solid fa-location-dot"></i> 123 Nguyễn Trãi, Thanh Xuân, Hà Nội</p>
            <p><i class="fa-solid fa-phone"></i> 0123 456 789</p>
            <p><i class="fa-regular fa-envelope"></i> support@ticktockshop.com</p>

            <h4>Giờ làm việc</h4>
            <p>Thứ 2 - Thứ 7: 8:00 - 21:30</p>
            <p>Chủ nhật & ngày lễ: 9:00 - 18:00</p>

            
        </div>
    </section>

    <script src="{{ asset('js/client/home.js') }}"></script>
    <script src="{{ asset('js/client/app.js') }}"></script>
    <script>
    const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/layouts/auth.js') }}"></script>
    @yield('scripts')
    @include('client.partials.quickview-modal')

</body>
</html>
