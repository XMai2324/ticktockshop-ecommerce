<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TickTock_Shop - ADMIN DASHBOARD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('storage/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/client/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/warranty.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/product_ad.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/promotion_ad.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ratings.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Placeholder để child view có thể thêm CSS riêng --}}
    @yield('styles')
</head>

<body>
    <header>
        <div class="logo">
            <img src="{{ asset('storage/logo2.png') }}" alt="logoShop">
        </div>

        <div class="header_menu">
            <li><a href="{{ route('admin.statistical')}}">THỐNG KÊ</a></li>
            <li><a href="{{ route('admin.products_index') }}">SẢN PHẨM</a></li>
            <li><a href="{{ route('admin.orders.index') }}">XỬ LÝ ĐƠN HÀNG</a></li>
            <li><a href="{{ route('admin.warranty') }}">THÔNG TIN BẢO HÀNH</a></li>
            <li><a href="{{ route('admin.ratings.index') }}">ĐÁNH GIÁ</a></li>
            <li><a href="{{ route('admin.promotions_index') }}">KHUYẾN MÃI</a></li>
        </div>
            <li class="header-user">
                <i class="fa fa-user"></i>
                <span class="user-name">{{ Auth::user()->name }}</span>
            </li>
            <li class="logout-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn" title="Đăng xuất">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
                </form>
            </li>

    </header>





    <main style="margin-top: 100px">
        @yield('content')
    </main>






    <script>
        const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/layouts/auth.js') }}"></script>
    <script src="{{ asset('js/client/home.js') }}"></script>
    {{-- Placeholder để child view có thể thêm JS riêng --}}
    @yield('scripts')
</body>

</html>
