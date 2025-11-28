
<div class="login-form" id="login-form">
    <form action="{{ route('client.login') }}" method="POST">
        @csrf
        <h3>Đăng nhập</h3>
        {{-- HIỂN THỊ LỖI TRONG FORM ĐĂNG NHẬP --}}
        @if (session('error') && session('login_error'))
            <div class="error-box">
                {{ session('error') }}
            </div>
        @endif

        @if (session('login_error') && $errors->any())
            <div class="error-box">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- HẾT PHẦN HIỂN THỊ LỖI --}}

        <div class="warranty-warning" style="color: red; margin-bottom: 10px; display: none;">
            Vui lòng đăng nhập để tra cứu bảo hành.
        </div>
        <input type="text" name="email" placeholder="Email đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button class="btn-login" type="submit" id="to-login">Đăng nhập</button>

        <div class="register-link">
            <span>Chưa có tài khoản?</span>
            <button class="dk" type="button" id="to-register">Đăng ký</button>
        </div>

        <a class="forgot_pass" href="#" id="to-forgot">Quên mật khẩu ?</a>



        

    </form>

</div>
