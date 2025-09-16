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
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('storage/logo2.png')}}" alt="logoShop">
        </div>

        <div class="header_menu">
            <li>
                <a href="">THƯƠNG HIỆU</a>
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
        <div class="footer-container">
            <p>Tải ứng dụng TickTock</p>
            <div class="app-google">
                <a href=""><img src="{{ asset('storage/appstore.png')}}" alt=""></a>
                <a href=""><img src="{{ asset('storage/googleplay.png')}}" alt=""></a>
            </div>
            <p>Nhận bản tin TickTock</p>
            <div class="input-email">
                <input type="text" placeholder="Nhập email của bạn">
                <i class="fas fa-arrow-left"></i>
            </div>
            <div class="footer-items">
                <li><a href=""><img src="{{ asset('storage/dathongbao.png')}}" alt=""></a></li>
                <li><a href=""></a>Liên hệ</li>
                <li><a href=""></a>Tuyển dụng</li>
                <li><a href=""></a>Giới thiệu</li>
                <li>
                    <a href=""><i class="fab fa-facebook-f"></i></a>
                    <a href=""><i class="fab fa-youtube"></i></a>
                    <a href=""><i class="fab fa-twitter"></i></a>
                </li>
            </div>
            <div class="footer-text">
                Công ty Cổ phần Dự Kim với số đăng ký kinh doanh: 0105777650 <br>
                Địa chỉ đăng ký: Tổ dân phố Tháp, P.Đại Mỗ, Q.Nam Từ Liêm, TP.Hà Nội, Việt Nam - 0243 205 2222 <br>
                Đặt hàng online: <b>0246 662 3434</b>
            </div>
            <div class="footer-bottom">
                @Ivymoda All rights reserved
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