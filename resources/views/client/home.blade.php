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

    @if (session('error'))
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

</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('storage/logo2.png')}}" alt="logoShop">
        </div>

        <div class="header_menu">
            <li> <a href="">THƯƠNG HIỆU</a>
                <ul class="sub_TH">
                    <li><a href="{{ route('products.filter', ['brand' => 'casio']) }}">Casio</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'rolex']) }}">Rolex</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'citizen']) }}">Citizen</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'rado']) }}">Rado</a></li>
                    <li><a href="{{ route('products.filter', ['brand' => 'seiko']) }}">Seiko</a></li>
                </ul>
            </li>
            <li> <a href="">NỮ</a>
                <ul class="sub_Nu">
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'casio']) }}">Casio nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'rolex']) }}">Rolex nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'citizen']) }}">Citizen nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'rado']) }}">Rado nữ</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'seiko']) }}">Seiko nữ</a></li>
                </ul>
            </li>
            <li> <a href="">NAM</a> 
                <ul class="sub_Nam">

                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'casio']) }}">Casio nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'rolex']) }}">Rolex nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'citizen']) }}">Citizen nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'rado']) }}">Rado nam</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'seiko']) }}">Seiko nam</a></li>
                </ul>
            <li> <a href="">CẶP ĐÔI</a>
                <ul class="sub_Doi">
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'casio']) }}">Casio đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'rolex']) }}">Rolex đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'rado']) }}">Rado đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'citizen']) }}">Citizen đôi</a></li>
                    <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'seiko']) }}">Seiko đôi</a></li>
                </ul>
            </li>
            <li> <a href="">PHỤ KIỆN</a> 
                <ul class="sub_pk">
                    <li><a href="{{ route('accessories.straps') }}">Dây đeo</a></li>
                    <li><a href="{{ route('accessories.boxes') }}">Hộp Đựng</a></li>
                    <li><a href="{{ route('accessories.glasses') }}">Kính cường lực</a></li>

                </ul>
            </li>
            <li> <a href="{{ route('warranty.form') }}">THÔNG TIN BẢO HÀNH</a> </li>
        </div>

        <div class="header_other">
            <li class="search-wrapper">
                <form id="searchForm" action="{{ route('products.filter') }}" method="GET" class="search-form">
                    <input id="searchInput" name="keyword" placeholder="Tìm kiếm" type="text" autocomplete="off">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>

                <div class="search-history" id="searchHistory">
                    <h3 class="search-heading">Lịch sử tìm kiếm</h3>
                    <ul class="search-history-list"></ul>
                </div>
            </li>
           
            <li class="header-user">
                    <i class="fa fa-user"></i>

                    @auth
                        <span class="user-name">{{ Auth::user()->name }}</span>
                    @else
                        <a title="Đăng nhập" id="login-icon" href="javascript:void(0);">Đăng nhập</a>
                    @endauth
                </li>



                <div class="overlay" id="login-overlay">
                        {{-- Form đăng nhập --}}
                        @include('client.auth.login')

                        {{-- Form đăng ký --}}
                        @include('client.auth.register')
                        
                        {{-- Form đặt lại mật khẩu --}}
                        @include('client.auth.reset_pass')
                    
                </div>
            </li>
            <li>  <a href="{{ route('cart.index') }}" class="cart-icon">
                <i class="fa fa-shopping-bag"></i>
                @if(session('cart') && array_sum(array_column(session('cart'), 'quantity')) > 0)
                    <span class="cart-count">
                        {{ array_sum(array_column(session('cart'), 'quantity')) }}
                    </span>
                @endif
                </a>
            </li>

            @auth
                <li class="logout-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fa fa-sign-out-alt"></i>
                        </button>
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
        </div>
        <div class="right_footer">
            <h4>Liên hệ</h4>
            <p><i class="fa-solid fa-location-dot"></i> 123 Nguyễn Trãi, Thanh Xuân, Hà Nội</p>
            <p><i class="fa-solid fa-phone"></i> 0123 456 789</p>
            <p><i class="fa-regular fa-envelope"></i> support@ticktockshop.com</p>
            
            <h4>Giờ làm việc</h4>
            <p>Thứ 2 - Thứ 7: 8:00 - 21:30</p>
            <p>Chủ nhật & ngày lễ: 9:00 - 18:00</p>

            <h4>Kết nối với chúng tôi</h4>
            <div class="social-links">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
    </section>

    <script src="{{ asset('js/client/home.js') }}"></script>
    <script src="{{ asset('js/client/app.js') }}"></script>
    <script>
    const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/layouts/auth.js') }}"></script>
    @yield('scripts')
</body>
</html>