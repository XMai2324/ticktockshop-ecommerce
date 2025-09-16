<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="quickview-slug-pattern" content="/quick-view/{slug}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Giỏ hàng')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo1.png') }}">

    <link rel="stylesheet" href="{{ asset('css/client/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/accessories.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/warranty.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/cart.css') }}">

    @if (session('error')) <meta name="login-error" content="1">@endif
    @if (session('register_error')) <meta name="register-error" content="1">@endif
</head>
<body>

<<<<<<< HEAD
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
            <li> <a href="{{ route('warranty.form') }}">THÔNG TIN BẢO HÀNH</a> </li>
        </div>
=======
<header>
    <div class="logo">
        <img src="{{ asset('storage/logo2.png')}}" alt="logoShop">
    </div>

    <div class="header_menu">
        <li><a href="">THƯƠNG HIỆU</a>
            <ul class="sub_TH">
                <li><a href="{{ route('products.filter', ['brand' => 'casio']) }}">Casio</a></li>
                <li><a href="{{ route('products.filter', ['brand' => 'rolex']) }}">Rolex</a></li>
                <li><a href="{{ route('products.filter', ['brand' => 'citizen']) }}">Citizen</a></li>
                <li><a href="{{ route('products.filter', ['brand' => 'rado']) }}">Rado</a></li>
                <li><a href="{{ route('products.filter', ['brand' => 'seiko']) }}">Seiko</a></li>
            </ul>
        </li>
        <li><a href="">NỮ</a>
            <ul class="sub_Nu">
                <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'casio']) }}">Casio nữ</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'rolex']) }}">Rolex nữ</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'citizen']) }}">Citizen nữ</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'rado']) }}">Rado nữ</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nu', 'brand' => 'seiko']) }}">Seiko nữ</a></li>
            </ul>
        </li>
        <li><a href="">NAM</a>
            <ul class="sub_Nam">
                <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'casio']) }}">Casio nam</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'rolex']) }}">Rolex nam</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'citizen']) }}">Citizen nam</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'rado']) }}">Rado nam</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'nam', 'brand' => 'seiko']) }}">Seiko nam</a></li>
            </ul>
        </li>
        <li><a href="">CẶP ĐÔI</a>
            <ul class="sub_Doi">
                <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'casio']) }}">Casio đôi</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'rolex']) }}">Rolex đôi</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'rado']) }}">Rado đôi</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'citizen']) }}">Citizen đôi</a></li>
                <li><a href="{{ route('products.filter', ['category' => 'cap-doi', 'brand' => 'seiko']) }}">Seiko đôi</a></li>
            </ul>
        </li>
        <li><a href="">PHỤ KIỆN</a>
            <ul class="sub_pk">
                <li><a href="{{ route('accessories.straps') }}">Dây đeo</a></li>
                <li><a href="{{ route('accessories.boxes') }}">Hộp Đựng</a></li>
                <li><a href="{{ route('accessories.glasses') }}">Kính cường lực</a></li>
            </ul>
        </li>
        <li><a href="{{ route('warranty.form') }}">THÔNG TIN BẢO HÀNH</a></li>
    </div>
>>>>>>> 6fb48dd72ac4be54a2a26ff5b43d6a47ec6ea6c8

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
            @include('client.auth.login')
            @include('client.auth.register')
        </div>

        <a href="{{ route('cart.index') }}" class="cart-icon">
            <i class="fa fa-shopping-bag"></i>
            @php
                $cartItems = session('cart') ?? [];
                $totalQty  = array_sum(array_column($cartItems, 'quantity'));
            @endphp
            @if($totalQty > 0)
                <span class="cart-count" id="cart-count">{{ $totalQty }}</span>
            @endif
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

<section class="cart">
    <div class="container">
        {{-- Tiến trình 3 bước --}}
        <div class="cart-top-wrap">
            <div class="cart-top">
                <div class="cart-top-cart cart-top-item"><i class="fa-solid fa-cart-shopping"></i></div>
                <div class="cart-top-adress cart-top-item"><i class="fa-solid fa-location-dot"></i></div>
                <div class="cart-top-payment cart-top-item"><i class="fa-solid fa-money-check-dollar"></i></div>
            </div>
        </div>
    </div>

    @php
        // An toàn: nếu controller chưa truyền $cartTotal thì tự tính
        $cartItems = session('cart') ?? [];
        $cartTotalView = isset($cartTotal)
            ? $cartTotal
            : array_reduce($cartItems, fn($t,$i) => $t + ($i['price'] * $i['quantity']), 0);
    @endphp

    <div class="container">
        <div class="cart-content row">
            {{-- Bảng sản phẩm --}}
            <div class="cart-content-left">
                <table>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>SL</th>
                        <th>Tổng tiền</th>
                        <th>Xóa</th>
                    </tr>

                    @forelse ($cartItems as $key => $item)
                        <tr>
                            <td>
                                <img
                                  src="{{ asset('storage/' . (
                                        $item['type'] === 'product'
                                            ? 'Watch/' . ($item['category'] === 'Nam'
                                                ? 'Watch_nam'
                                                : ($item['category'] === 'Cặp đôi' ? 'Watch_cap' : 'Watch_nu'))
                                            : 'accessories/' . $item['type']
                                    ) . '/' . $item['image']) }}"
                                  alt="{{ $item['name'] }}">
                            </td>

                            <td><p>{{ $item['name'] }}</p></td>
                            <td>
                                <p>{{ number_format($item['price'], 0, ',', '.') }} <sub>đ</sub></p>
                            </td>

                            <td style="text-align:center;">
                                <input type="number"
                                       min="1"
                                       value="{{ $item['quantity'] }}"
                                       data-row-id="{{ $key }}"
                                       class="qty-input">
                            </td>

                            <td>
                                <p id="subtotal-{{ $key }}">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} <sub>đ</sub>
                                </p>
                            </td>

                            <td>
                                <form action="{{ route('cart.remove', $key) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="remove-btn">X</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">Giỏ hàng trống.</td></tr>
                    @endforelse
                </table>
            </div>

            {{-- Tóm tắt đơn hàng --}}
            <div class="cart-content-right">
                <table>
                    <tr><th colspan="2">TỔNG TIỀN GIỎ HÀNG</th></tr>
                    <tr>
                        <td>Tổng sản phẩm</td>
                        <td>
                            <span id="cart-total-qty">
                                {{ array_sum(array_column($cartItems, 'quantity')) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>TỔNG TIỀN HÀNG</td>
                        <td><p id="cart-total-goods">{{ number_format($cartTotal, 0, ',', '.') }} <sub>đ</sub></p></td>
                    </tr>
                    <tr>
                        <td>Tạm tính</td>
                        <td><p id="cart-total" style="color:black;font-weight:bold;">{{ number_format($cartTotal, 0, ',', '.') }} <sub>đ</sub></p></td>
                    </tr>
                </table>

                <div class="cart-content-right-button">
                    <a href="{{ route('checkout') }}"><button>Đặt Hàng</button></a>
                </div>

                <div class="cart-content-right-dangnhap">
                    @auth
                        <p>Tài khoản: {{ auth()->user()->name }}</p>
                        <p>Chào mừng bạn quay lại!</p>
                    @else
                        <p>Hãy <a href="{{ route('client.login') }}" style="color:blue;font-weight:bold;">Đăng nhập</a> để được tích điểm thành viên</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<<<<<<< HEAD
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
=======
<section class="footer">
    <div class="footer-container">
        <p>Tải ứng dụng TickTock</p>
        <div class="app-google">
            <a href=""><img src="{{ asset('storage/appstore.jpg')}}" alt=""></a>
            <a href=""><img src="{{ asset('storage/googleplay.jpg')}}" alt=""></a>
>>>>>>> 6fb48dd72ac4be54a2a26ff5b43d6a47ec6ea6c8
        </div>
        <p>Nhận bản tin TickTock</p>
        <div class="input-email">
            <input type="text" placeholder="Nhập email của bạn">
            <i class="fas fa-arrow-left"></i>
        </div>
        <div class="footer-items">
            <li><a href=""><img src="{{ asset('storage/dathongbao.jpg')}}" alt=""></a></li>
            <li><a href="#">Liên hệ</a></li>
            <li><a href="#">Tuyển dụng</a></li>
            <li><a href="#">Giới thiệu</a></li>
            <li>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </li>
        </div>
        <div class="footer-text">
            Công ty Cổ phần Dự Kim với số đăng ký kinh doanh: 0105777650 <br>
            Địa chỉ đăng ký: Tổ dân phố Tháp, P.Đại Mỗ, Q.Nam Từ Liêm, TP.Hà Nội, Việt Nam - 0243 205 2222 <br>
            Đặt hàng online: <b>0246 662 3434</b>
        </div>
        <div class="footer-bottom">@Ivymoda All rights reserved</div>
    </div>
</section>

{{-- JS hiện có --}}
<script src="{{ asset('js/client/home.js') }}"></script>
<script src="{{ asset('js/client/app.js') }}"></script>
<script>const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};</script>
<script src="{{ asset('js/layouts/auth.js') }}"></script>
<script src="{{ asset('js/client/quickview.js') }}" defer></script>
<script src="{{ asset('js/client/cart.js') }}" defer></script>

<script>window.CART_UPDATE_URL = "{{ route('cart.update') }}";</script>

@yield('scripts')
</body>
</html>