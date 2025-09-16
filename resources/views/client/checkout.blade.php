
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
        <link rel="stylesheet" href="{{ asset('css/client/products.css') }}">
        <link rel="stylesheet" href="{{ asset('css/client/accessories.css') }}">
        <link rel="stylesheet" href="{{ asset('css/client/checkout.css') }}">

        @if (session('error')) <meta name="login-error" content="1">@endif
        @if (session('register_error')) <meta name="register-error" content="1">@endif
    </head>

    @section('content')
    <header>
        <div class="logo">
            <img src="{{ asset('storage/logo2.png')}}" alt="logoShop">
        </div>

        <div class="header_menu">
            <li> <a href="#">THƯƠNG HIỆU</a>
                <ul class="sub_TH">
                    <li><a href="#">Rolex</a></li>
                    <li><a href="#">Casio</a></li>
                    <li><a href="#">Citizen</a></li>
                    <li><a href="#">Rado</a></li>
                    <li><a href="#">Seiko</a></li>
                </ul>
            </li>
            <li> <a href="#">NỮ</a>
                <ul class="sub_Nu">
                    <li><a href="#">Rolex nữ</a></li>
                    <li><a href="#">Casio nữ</a></li>
                    <li><a href="#">Citizen nữ</a></li>
                    <li><a href="#">Rado nữ</a></li>
                    <li><a href="#">Seiko nữ</a></li>
                </ul>
            </li>
            <li> <a href="#">NAM</a> 
                <ul class="sub_Nam">
                    <li><a href="#">Rolex nam</a></li>
                    <li><a href="#">Casio nam</a></li>
                    <li><a href="#">Citizen nam</a></li>
                    <li><a href="#">Rado nam</a></li>
                    <li><a href="#">Seiko nam</a></li>
                </ul>
            </li>
            <li> <a href="#">CẶP ĐÔI</a>
                <ul class="sub_Doi">
                    <li><a href="#">Rolex cặp</a></li>
                    <li><a href="#">Casio cặp</a></li>
                    <li><a href="#">Citizen cặp</a></li>
                    <li><a href="#">Rado cặp</a></li>
                    <li><a href="#">Seiko cặp</a></li>
                </ul>
            </li>
            <li> <a title="sản phẩm mới" href="#">PHỤ KIỆN</a> </li>
            <li> <a href="#">THÔNG TIN</a> </li>
        </div>

        <div class="header_other">
            <li>
                <input placeholder="Tìm kiếm" type="text"> <i class="fas fa-search"></i>
                <div class="search-history">
                    <h3 class="search-heading">Lịch sử tìm kiếm</h3>
                    <ul class="search-history-list">
                        <li class="item"><a href="#">Casio</a></li>
                        <li class="item"><a href="#">Rolex</a></li>
                    </ul>
                </div>
            </li>
            <li><a class="fa fa-user" href="#"></a></li>
            <li><a class="fa fa-shopping-bag" href="#"></a></li>
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

            <form method="POST" action="{{ route('checkout.placeOrder') }}" id="checkout-form">
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
                            @forelse($cartItems as $item)
                                @php
                                    // Hỗ trợ cả array và object
                                    $name  = is_array($item) ? ($item['name'] ?? '') : ($item->name ?? '');
                                    $qty   = is_array($item) ? ($item['qty'] ?? ($item['quantity'] ?? 1))
                                                            : ($item->qty ?? ($item->quantity ?? 1));
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
                                        @foreach($promotions as $p)
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
                </div>
            </form>
        </div>
    </section>

    <section class="footer">
        <div class="footer-container">
            <p>Tải ứng dụng TickTock</p>
            <div class="app-google">
                <a href=""><img src="{{ asset('storage/appstore.jpg')}}" alt=""></a>
                <a href=""><img src="{{ asset('storage/googleplay.jpg')}}" alt=""></a>
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
                Công ty Cổ phần TickTock với số đăng ký kinh doanh: 0105777650 <br>
                Địa chỉ đăng ký: 273 An Dương vương, P.7, Q.5, TP.Hồ Chí Minh, Việt Nam - 0243 205 2222 <br>
                Đặt hàng online: <b>0246 662 3434</b>
            </div>
            <div class="footer-bottom">
                @TickTock All rights reserved
            </div>
        </div>
    </section>

    <script src="{{ asset('js/client/home.js') }}"></script>
    <script src="{{ asset('js/client/app.js') }}"></script>
    <script>const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};</script>
    <script src="{{ asset('js/layouts/auth.js') }}"></script>
    <script src="{{ asset('js/client/quickview.js') }}" defer></script>
    <script src="{{ asset('js/client/checkout.js') }}" defer></script>

    @yield('scripts')
    </body>
</html>
