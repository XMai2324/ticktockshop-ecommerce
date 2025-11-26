@include('client.alert')

<div class="login-form" id="login-form">
    <form action="{{ route('client.login.submit') }}" method="POST">
        @csrf
        <h3>Đăng nhập</h3>

        {{-- THÔNG BÁO KHI TRUY CẬP BẢO HÀNH MÀ CHƯA LOGIN --}}
        <div class="warranty-warning" style="color: red; margin-bottom: 10px; display: none;">
            Vui lòng đăng nhập để tra cứu bảo hành.
        </div>

        {{-- HIỂN THỊ LỖI LOGIN (Email sai, mật khẩu sai, tài khoản bị khóa...) --}}
        @if(session('error'))
            <div style="color: red; margin-bottom: 10px;">
                {{ session('error') }}
            </div>
        @endif

        {{-- HIỂN THỊ LỖI VALIDATE --}}
        @if($errors->any() && session('login_error'))
            <div style="color: red; margin-bottom: 10px;">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <input type="text" name="email" placeholder="Email đăng nhập" required value="{{ old('email') }}">
        <input type="password" name="password" placeholder="Mật khẩu" required>

        <button class="btn-login" type="submit" id="to-login">Đăng nhập</button>

        <div class="register-link">
            <span>Chưa có tài khoản?</span>
            <button class="dk" type="button" id="to-register">Đăng ký</button>
        </div>

        <a class="forgot_pass" href="#" id="to-forgot">Quên mật khẩu ?</a>
    </form>
</div>
