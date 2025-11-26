@extends('admin.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/customers_ad.css') }}">
@endsection

@section('content')
<div class="customer-page">

    <h2 class="customer-title">Quản lý khách hàng</h2>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARD FORM THÊM --}}
    <div class="card-customer">
        <div class="card-header">Thêm khách hàng mới</div>

        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            <div class="customer-form-row">
                <div class="customer-form-group">
                    <label>Tên khách hàng</label>
                    <input type="text" name="name" placeholder="VD: Nguyễn Văn A" required>
                </div>

                <div class="customer-form-group">
                    <label>Email đăng nhập</label>
                    <input type="email" name="email" placeholder="mail@example.com" required>
                </div>

                <div class="customer-form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" placeholder="Mặc định: 123456" required>
                </div>

                <div class="customer-form-group">
                    <label>Số điện thoại</label>
                    <input type="text" name="phone" placeholder="098xxxxx">
                </div>

                <div class="customer-form-group">
                    <label>Địa chỉ</label>
                    <input type="text" name="address" placeholder="Hà Nội, TP.HCM,...">
                </div>

                <div class="customer-form-group">
                    <label>Role</label>
                    <select name="role">
                        <option value="user">Khách hàng</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div style="margin-top: 10px;">
                <button class="btn btn-primary">+ Thêm khách hàng</button>
            </div>
        </form>
    </div>

    {{-- CARD BẢNG KHÁCH HÀNG --}}
    <div class="customer-table-wrapper">
        <div class="customer-table-title">Danh sách khách hàng</div>

        <table class="customer-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Total orders</th>
                    <th>Status</th>
                    <th style="width:210px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email }}</td>
                    <td>********</td>
                    <td>{{ $c->phone }}</td>
                    <td>{{ $c->address }}</td>
                    <td>{{ $c->role }}</td>
                    <td>{{ $c->orders_count }}</td>
                    <td>
                        @if($c->is_active)
                            <span class="badge badge-success">Hoạt động</span>
                        @else
                            <span class="badge badge-muted">Vô hiệu</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.customers.reset-password', $c->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button class="btn btn-warning btn-sm"
                                onclick="return confirm('Reset mật khẩu về 123456?')">
                                Reset
                            </button>
                        </form>

                        {{-- Khóa / Mở tài khoản --}}
                        <form action="{{ route('admin.customers.toggle-active', $c->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @if($c->is_active)
                                {{-- Đang hoạt động -> hiện nút Khóa --}}
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Khóa tài khoản này?')">
                                    Khóa
                                </button>
                            @else
                                {{-- Đang vô hiệu -> hiện nút Mở --}}
                                <button class="btn btn-success btn-sm"
                                    onclick="return confirm('Mở khóa (kích hoạt lại) tài khoản này?')">
                                    Mở
                                </button>
                            @endif
                        </form>

                        <form action="{{ route('admin.customers.destroy', $c->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-dark btn-sm"
                                onclick="return confirm('Bạn chắc chắn muốn xóa khách hàng này?')">
                                Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 10px;">
            {{ $customers->links() }}
        </div>
    </div>

</div>
@endsection
