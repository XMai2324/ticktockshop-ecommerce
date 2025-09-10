<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TickTock_Shop - ADMIN DASHBOARD</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/client/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client/warranty.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/accessories_ad.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('storage/logo2.png')}}" alt="logoShop">
        </div>

        <div class="header_menu">
            <li><a href="">QUẢN LÝ SẢN PHẨM</a>
                <ul class="sub_menu">
                    <li><a href="{{ route('admin.products_index') }}">Đồng hồ</a></li>
                    <li><a href="{{ route('admin.accessories.straps') }}">Dây đeo</a></li>
                    <li><a href="{{ route('admin.accessories.boxes') }}">Hộp đựng</a></li>
                    <li><a href="{{ route('admin.accessories.glasses') }}">Kính cường lực</a></li>
                </ul>
        </li>
            <li><a href="#">XỬ LÝ ĐƠN HÀNG</a></li>
            <li><a href="{{ route('admin.warranty') }}">THÔNG TIN BẢO HÀNH</a></li>
            <li><a href="#">ĐÁNH GIÁ</a></li>
        </div>

        <div class="header_other">
            <li> <input placeholder="Tìm kiếm" type="text"> <i class="fas fa-search"></i>
                <div class="search-history">
                    <h3 class="search-heading">Lịch sử tìm kiếm</h3>
                    <ul class="search-history-list">
                        <li class="item"> <a href="">Casio</a> </li>
                        <li class="item"> <a href="">Rolex</a> </li>

                    </ul>
                </div>
            </li>

            <li class="header-user">
                <i class="fa fa-user"></i>
                <span class="user-name">{{ Auth::user()->name }}</span>
            </li>
            <li class="logout-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
                </form>
            </li>
        </div>
    </header>

    
    <main style="margin-top: 100px">
        @yield('content')
    </main>

   
    <script>
        const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/layouts/auth.js') }}"></script>
        <script src="{{ asset('js/client/home.js') }}"></script>
</body>
</html>