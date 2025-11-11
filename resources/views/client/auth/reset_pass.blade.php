
<div class="login-form" id="forgot-form" style="display:none;">
  <form action="{{ route('client.pass_update') }}" method="POST">
    @csrf
    <h3>Đặt lại mật khẩu</h3>

    @if ($errors->any() && session('forgot_error'))
      <div style="color:red; margin-bottom:10px;">
        @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
      </div>
    @endif

    @if (session('forgot_status'))
      <div style="color:green; margin-bottom:10px;">{{ session('forgot_status') }}</div>
    @endif

    <input type="email" name="email" placeholder="Email đăng nhập" required>
    <input type="password" name="password" placeholder="Mật khẩu mới" required>
    <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>

    <button class="btn-login" type="submit">Cập nhật mật khẩu</button>

    <div class="register-link">
      <button type="button" class="dk" id="to-login-from-forgot">Quay lại đăng nhập</button>
    </div>
  </form>
</div>
