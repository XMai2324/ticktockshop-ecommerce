
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="quickview-slug-pattern" content="/quick-view/{slug}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Thanh Toán')</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="icon" type="image/png" href="{{ asset('storage/logo1.png') }}">


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="quickview-slug-pattern" content="/quick-view/{slug}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Thanh Toán')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/client/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/checkout.css') }}">
        <link rel="icon" type="image/png" href="{{ asset('storage/logo.png') }}">
        <link rel="stylesheet" href="{{ asset('css/client/home.css') }}">
        <link rel="stylesheet" href="{{ asset('css/client/products.css') }}">
        <link rel="stylesheet" href="{{ asset('css/client/accessories.css') }}">
        <link rel="stylesheet" href="{{ asset('css/client/checkout.css') }}">

    @if (session('error')) <meta name="login-error" content="1">@endif
    @if (session('register_error')) <meta name="register-error" content="1">@endif
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
#qr-container {
    display: none;
    margin-top: 20px;
    padding: 15px;
    border: 1px dashed #3a8bff;
    border-radius: 12px;
    background: #f8fbff;
    width: fit-content;
    text-align: center;
}

#qr-container p {
    margin-bottom: 10px;
}

#qr-image {
    width: 260px;
    height: auto;
    border-radius: 8px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
}

#qr-timer {
    margin-top: 8px;
    color: #ff2e2e;
    font-weight: bold;
    font-size: 15px;
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

        {{-- NỮ --}}
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

<!-- <<<<<<< HEAD
<<<<<<< HEAD
                        <div class="delivery-content-left-input-top row">
                            <div class="delivery-content-left-input-top-item">
                                <label for="name">Họ tên <span style="color:red;">*</span></label>
                                <input id="name" name="fullname" type="text" placeholder="Nhập họ tên của bạn" value="{{ old('fullname') }}">
                            </div>
                            <div class="delivery-content-left-input-top-item">
                                <label>Điện thoại <span style="color:red;">*</span></label>
                                <input name="phone" type="text" placeholder="Nhập Điện thoại của bạn" value="{{ old('phone') }}">
                            </div>
                            <div class="delivery-content-left-input-top-item">
                                <label>Email <span style="color:red;">*</span></label>
                                <input name="email" type="email" placeholder="Nhập Email của bạn" value="{{ old('email') }}">
                            </div>
                            <div class="delivery-content-left-input-top-item">
                                <label>Tỉnh/Tp <span style="color:red;">*</span></label>
                                <input name="province" type="text" placeholder="Nhập Tỉnh/Tp của bạn" value="{{ old('province') }}">
                            </div>
                        </div>

                        <div class="delivery-content-left-input-bottom">
                            <label>Quận/Huyện <span style="color:red;">*</span></label>
                            <input name="district" type="text" placeholder="Chọn Quận/Huyện" value="{{ old('district') }}">
                        </div>

                        <div class="delivery-content-left-input-bottom">
                            <label>Địa chỉ <span style="color:red;">*</span></label>
                            <input name="address" type="text" placeholder="Chọn Địa chỉ" value="{{ old('address') }}">
                        </div>

                        {{-- Phương thức thanh toán --}}
                        <div class="payment-method">
                            <p style="font-weight:bold; margin-top:20px;">Phương thức thanh toán</p>
                            <div class="row">
                                <label style="margin-right:20px;">
                                    <input type="radio" name="payment_method" value="cash" {{ old('payment_method','cash')==='cash'?'checked':'' }}> Thanh toán khi nhận hàng
                                </label>
                                <label>
                                    <input type="radio" name="payment_method" value="bank" {{ old('payment_method')==='bank'?'checked':'' }}> Thanh toán bằng ngân hàng
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="delivery-content-right">
                        <table>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>

                            @forelse ($cartItems as $item)
                                @php $line = (int)$item['qty'] * (int)$item['price']; @endphp
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['qty'] }}</td>
                                    <td><p>{{ number_format($line, 0, ',', '.') }} <sup>đ</sup></p></td>
                                </tr>
                            @empty
                                <tr><td colspan="3">Giỏ hàng trống</td></tr>
                            @endforelse
                            <tr class="total">
                                <td colspan="2" style="font-weight:bold;">Tổng</td>
                                <td style="font-weight:bold;">
                                    <p>{{ number_format($subtotal, 0, ',', '.') }} <sup>đ</sup></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">Phí vận chuyển</td>
                                <td><p>{{ number_format($shipping, 0, ',', '.') }} <sup>đ</sup></p></td>
                            </tr>
                            <tr>
                                <td colspan="2">Giảm giá</td>
                                <td><p>-{{ number_format($discount, 0, ',', '.') }} <sup>đ</sup></p></td>
                            </tr>
                            <tr class="grand-total">
                                <td colspan="2" style="font-weight:bold;">TỔNG TIỀN HÀNG</td>
                                <td style="font-weight:bold;">
                                    <p id="grand-total-text" data-base="{{ $subtotal + $shipping - $discount }}">
                                        {{ number_format($grandTotal, 0, ',', '.') }} <sup>đ</sup>
                                    </p>
                                </td>
                            </tr>

                            {{-- Mã khuyến mãi --}}
                            <tr>
                                <td colspan="2" style="font-weight:bold;">Mã Khuyến Mãi</td>
                                <td>
                                    <select id="promo-select" class="coupon-select" name="promo_code">
                                        <option value="">Chọn mã có sẵn</option>
                                        @foreach($availableCoupons as $p)
                                            <option value="{{ $p['code'] }}"
                                                data-type="{{ $p['type'] }}"
                                                data-value="{{ $p['value'] }}">
                                                {{ $p['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>

                        <div class="delivery-content-left-button" style="justify-content:flex-end; margin-top:20px;">
                            <a href="{{ url('/cart') }}" style="margin-right:auto;"><span>&#171;</span> Quay lại giỏ hàng</a>
                            <button type="submit" class="qr-payment"><span style="font-weight:bold;">THANH TOÁN</span></button>
                        </div>

                        {{-- QR khi chọn ngân hàng --}}
                        <div id="qr-container" style="display:none; margin-top:20px;">
                            <p><strong>Mã QR thanh toán:</strong></p>
                            <img id="qr-image" src="{{ asset('images/QR.png') }}" alt="QR thanh toán" style="width:300px; height:300px;">
                            <p id="qr-timer" style="color:red; font-weight:bold;">Thời gian còn lại: 60s</p>
                        </div>
                    </div>
                </div> -->
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
            <div class="user-menu">
                <i class="fa fa-user"></i>

                @auth
                    <span class="user-name">{{ Auth::user()->name }}</span>

                    <ul class="dropdown-menu-user">
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

<section class="delivery">
    <div class="container">
        <div class="delivery-top-wrap">
            <div class="delivery-top-delivery delivery-top-item">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="delivery-top-item active">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div class="delivery-top-payment delivery-top-item">
                <i class="fa-solid fa-money-check-dollar"></i>
            </div>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 16px;">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 16px;">
                <ul style="margin-left: 16px;">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" data-cart-count="{{ count(session('cart', [])) }}" action="{{ route('checkout.placeOrder') }}" id="checkout-form">
            @csrf
            <div class="delivery-content">
                <div class="delivery-content-left">
                    <p>Vui lòng chọn địa chỉ giao hàng</p>

                    <div class="delivery-content-left-input-top row">
                        <div class="delivery-content-left-input-top-item">
                            <label for="name">Họ tên <span style="color:red;">*</span></label>
                            <input id="name" name="fullname" type="text" placeholder="Nhập họ tên của bạn" value="{{ old('fullname') }}">
                        </div>
                        <div class="delivery-content-left-input-top-item">
                            <label>Điện thoại <span style="color:red;">*</span></label>
                            <input name="phone" type="text" placeholder="Nhập Điện thoại của bạn" value="{{ old('phone') }}">
                        </div>
                        <div class="delivery-content-left-input-top-item">
                            <label>Email <span style="color:red;">*</span></label>
                            <input name="email" type="email" placeholder="Nhập Email của bạn" value="{{ old('email') }}">
                        </div>
                        <div class="delivery-content-left-input-top-item">
                            <label>Tỉnh/Tp <span style="color:red;">*</span></label>
                            <input name="province" type="text" placeholder="Nhập Tỉnh/Tp của bạn" value="{{ old('province') }}">
                        </div>
                    </div>

                    <div class="delivery-content-left-input-bottom">
                        <label>Quận/Huyện <span style="color:red;">*</span></label>
                        <input name="district" type="text" placeholder="Chọn Quận/Huyện" value="{{ old('district') }}">
                    </div>

                    <div class="delivery-content-left-input-bottom">
                        <label>Địa chỉ <span style="color:red;">*</span></label>
                        <input name="address" type="text" placeholder="Chọn Địa chỉ" value="{{ old('address') }}">
                    </div>

                    {{-- Phương thức thanh toán --}}
                    <div class="payment-method">
                        <p style="font-weight:bold; margin-top:20px;">Phương thức thanh toán</p>
                        <div class="row">
                            <label style="margin-right:20px;">
                                <input type="radio" name="payment_method" value="cash" {{ old('payment_method','cash')==='cash'?'checked':'' }}> Thanh toán khi nhận hàng
                            </label>
                            <label>
                                <input type="radio" id="bank-payment" name="payment_method" value="bank" {{ old('payment_method')==='bank'?'checked':'' }}> Thanh toán bằng ngân hàng
                            </label>
                        </div>
                    </div>
                </div>

                <div class="delivery-content-right">
                    <table>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                        @forelse($cartItems as $item)
                            @php
                                $name  = is_array($item) ? ($item['name'] ?? '') : ($item->name ?? '');
                                $qty   = is_array($item) ? ($item['qty'] ?? ($item['quantity'] ?? 1)) : ($item->qty ?? ($item->quantity ?? 1));
                                $price = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
                                $line  = (int)$qty * (int)$price;
                            @endphp
                            <tr>
                                <td>{{ $name }}</td>
                                <td>{{ $qty }}</td>
                                <td><p>{{ number_format($line, 0, ',', '.') }} <sup>đ</sup></p></td>
                            </tr>
                        @empty
                            <tr><td colspan="3">Giỏ hàng trống</td></tr>
                        @endforelse

                        <tr class="total">
                            <td colspan="2" style="font-weight:bold;">Tổng</td>
                            <td style="font-weight:bold;">
                                <p>{{ number_format($subtotal, 0, ',', '.') }} <sup>đ</sup></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Phí vận chuyển</td>
                            <td><p>{{ number_format($shipping, 0, ',', '.') }} <sup>đ</sup></p></td>
                        </tr>
                        <tr>
                            <td colspan="2">Giảm giá</td>
                            <td>
                                <p id="discount-amount" data-raw="{{ session('coupon.discount_amount', $discount) }}">
                                    -{{ number_format(session('coupon.discount_amount', $discount), 0, ',', '.') }} <sup>đ</sup>
                                </p>
                            </td>
                        </tr>
                        <tr class="grand-total">
                            <td colspan="2" style="font-weight:bold;">TỔNG TIỀN HÀNG</td>
                            <td style="font-weight:bold;">
                                <p id="grand-total-text" data-base="{{ $subtotal + $shipping - session('coupon.discount_amount', $discount) }}">
                                    {{ number_format($grandTotal, 0, ',', '.') }} <sup>đ</sup>
                                </p>
                            </td>
                        </tr>
                    </table>

                    {{-- Khu chọn mã khuyến mãi (nằm ngoài bảng) --}}
                    <div class="coupon-area">
                        <div class="label">Mã Khuyến Mãi</div>
                        <div class="value">
                            @if(session('coupon'))
                                <span id="applied-coupon">
                                    {{ session('coupon.name') }} ({{ session('coupon.code') }})
                                </span>
                                <button type="button" id="btn-remove-coupon" class="btn-link small">Bỏ mã</button>
                            @else
                                <button type="button" id="btn-open-coupon" class="btn primary">Chọn mã khuyến mãi</button>
                            @endif
                        </div>
                    </div>

                    <div class="delivery-content-left-button" style="justify-content:flex-end; margin-top:20px;">
                        <a href="{{ url('/cart') }}" style="margin-right:auto;"><span>&#171;</span> Quay lại giỏ hàng</a>
                        <button type="submit" class="qr-payment"><span style="font-weight:bold;">THANH TOÁN</span></button>
                    </div>



                </div>
            </div>
        </form>
    </div>
</section>

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

{{-- Scripts --}}
<script src="{{ asset('js/client/home.js') }}"></script>
<script src="{{ asset('js/client/app.js') }}"></script>
<script>const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};</script>
<script src="{{ asset('js/layouts/auth.js') }}"></script>
<script src="{{ asset('js/client/quickview.js') }}" defer></script>

{{-- Config routes cho file JS riêng --}}
<script>
  window.CHECKOUT_ROUTES = {
    apply: "{{ route('checkout.applyCoupon') }}", // NOTE: ensure route name matches your routes file
    remove: "{{ route('checkout.removeCoupon') }}"
  };
</script>
<script src="{{ asset('js/client/checkout.js') }}" defer></script>


{{-- Modal chọn mã khuyến mãi (HTML) --}}
<div id="coupon-modal" class="modal hidden" aria-hidden="true">
  <div class="modal__overlay" data-close="coupon-modal"></div>
  <div class="modal__content" role="dialog" aria-modal="true" aria-labelledby="coupon-modal-title">
    <div class="modal__header">
      <h3 id="coupon-modal-title">Chọn mã khuyến mãi</h3>
      <button type="button" class="modal__close" data-close="coupon-modal">&times;</button>
    </div>

    <div class="modal__body">
      <div id="coupon-list" class="coupon-grid">
        @foreach(($availableCoupons ?? []) as $cp)
          <div class="coupon-card"
               data-code="{{ $cp->code }}"
               data-name="{{ $cp->name }}"
               data-type="{{ $cp->type }}"
               data-value="{{ $cp->value }}"
               data-max="{{ $cp->max_discount ?? 0 }}"
               data-min="{{ $cp->min_order_value ?? 0 }}"
               data-exp="{{ optional($cp->end_at)->format('d-m-Y') }}">
            <div class="coupon-card__head">
              <span class="coupon-code">{{ $cp->code }}</span>
              <span class="badge {{ $cp->type==='percent' ? 'percent':'fixed' }}">
                {{ $cp->type==='percent' ? $cp->value.'%' : number_format($cp->value,0,',','.') .' đ' }}
              </span>
            </div>
            <div class="coupon-card__body">
              <div class="coupon-name">{{ $cp->name }}</div>
              @if($cp->min_order_value)
                <div class="coupon-cond">ĐH tối thiểu: {{ number_format($cp->min_order_value,0,',','.') }} đ</div>
              @endif
              @if($cp->max_discount && $cp->type==='percent')
                <div class="coupon-cond">Giảm tối đa: {{ number_format($cp->max_discount,0,',','.') }} đ</div>
              @endif
              @if($cp->end_at)
                <div class="coupon-exp">Hạn dùng: {{ $cp->end_at->format('d-m-Y') }}</div>
              @endif
            </div>
            <div class="coupon-card__foot">
              <button type="button" class="btn use-coupon">Dùng mã này</button>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

</body>
<script>
    const form = document.getElementById('checkout-form');
    form.addEventListener('submit', function() {
        const data = {
            fullname: form.fullname.value,
            phone: form.phone.value,
            email: form.email.value,
            province: form.province.value,
            district: form.district.value,
            address: form.address.value
        };
        localStorage.setItem('checkout_info', JSON.stringify(data));
    });

    // Khi load form lại
    window.addEventListener('DOMContentLoaded', () => {
        const saved = JSON.parse(localStorage.getItem('checkout_info') || '{}');
        for (const key in saved) {
            const input = document.querySelector(`[name="${key}"]`);
            if (input) input.value = saved[key];
        }
    });
    </script>

    {{-- <script>
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const method = document.querySelector('input[name="payment_method"]:checked').value;

            if (method === 'bank') {
                e.preventDefault(); // Chặn submit mặc định

                const total = document.getElementById('grand-total-text').dataset.base;

                // Tạo form ẩn POST tới route vnpay/payment
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('vnpay.payment') }}";

                // Thêm CSRF token
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = "{{ csrf_token() }}";
                form.appendChild(tokenInput);

                // Thêm amount
                const amountInput = document.createElement('input');
                amountInput.type = 'hidden';
                amountInput.name = 'amount';
                amountInput.value = total;
                form.appendChild(amountInput);

                document.body.appendChild(form);
                form.submit(); // submit form POST trực tiếp
            }
        });
        </script> --}}

        <script>
            document.getElementById('checkout-form').addEventListener('submit', function(e) {
                const method = document.querySelector('input[name="payment_method"]:checked').value;

                if (method === 'bank') {
                    e.preventDefault();

                    const total = document.getElementById('grand-total-text').dataset.base;
                    const checkoutInfo = JSON.parse(localStorage.getItem('checkout_info') || '{}');

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('vnpay.payment') }}";

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = "{{ csrf_token() }}";
                    form.appendChild(tokenInput);

                    const amountInput = document.createElement('input');
                    amountInput.type = 'hidden';
                    amountInput.name = 'amount';
                    amountInput.value = total;
                    form.appendChild(amountInput);

                    // Gửi dữ liệu checkout_info
                    for (const key in checkoutInfo) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = checkoutInfo[key];
                        form.appendChild(input);
                    }

                    document.body.appendChild(form);
                    form.submit();
                }
            });
            </script>

</html>
