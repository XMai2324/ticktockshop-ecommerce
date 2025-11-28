@extends('client.home')

@section('content')

<div class="profile-container mt-4">

    <h3 class="mb-4">Hồ sơ cá nhân</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <!-- Thông tin tài khoản -->
        <div class="col-md-6">
            <div class="profile-card">
                <h5>Thông tin tài khoản</h5>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-3" style="margin-bottom: 15px;">
                        <label for="name" class="form-label">Tên tài khoản</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                    </div>

                    <div class="mb-3" style= "margin-bottom: 15px;">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>

                    <button class="btn-custom-primary">Cập nhật</button>
                </form>
            </div>
        </div>

        <!-- Đổi mật khẩu -->
        <div class="col-md-6">
            <div class="profile-card">
                <h5>Đổi mật khẩu</h5>

                <form action="{{ route('profile.changePassword') }}" method="POST">
                    @csrf
                    <div class="mb-3" style = "margin-bottom: 15px;">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-control">
                    </div>

                    <div class="mb-3" style="margin-bottom: 15px;">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nhập lại mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>

                    <button class="btn-custom-warning">Đổi mật khẩu</button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
<style>
    .profile-container {
        max-width: 900px;
        margin: auto;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
        
    }
    .profile-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        margin-bottom: 35px;
    }
 

    .profile-card h5 {
        font-weight: 600;
        margin-bottom: 20px;
        border-left: 4px solid #0d6efd;
        padding-left: 10px;
    }

    .form-control {
        border-radius: 8px;
        padding: 10px;
    }

    .btn-custom-primary {
        background: #0d6efd;
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
    }

    .btn-custom-primary:hover {
        background: #0b5ed7;
    }

    .btn-custom-warning {
        background: #ffc107;
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
    }

    .btn-custom-warning:hover {
        background: #e0a800;
    }
</style>
