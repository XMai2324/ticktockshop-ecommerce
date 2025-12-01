@extends('admin.dashboard')

@section('content')
<div class="container container-nhap-hang mt-4">
    <h2>Xem trước nhập hàng</h2>

    <div class="top-buttons">
        <form method="POST" action="{{ route('admin.nhapHang_confirm') }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-success">Xác nhận lưu</button>
        </form>
        <a href="{{ route('admin.nhapHang_export') }}" class="btn btn-primary" target="_blank">Xuất phiếu</a>
        <a href="{{ route('admin.nhapHang_index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <table class="table table-bordered table-preview">
        <thead>
            <tr>
                <th>Tên SP</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Giá nhập (vnd)</th>
                <th>Giá bán (vnd)</th>
                <th>Số lượng nhập</th>
            </tr>
        </thead>
        <tbody>
            @foreach($changed as $p)
            <tr>
                <td>{{ $p['name'] ?? '' }}</td>
                <td>{{ $p['category_name'] ?? '' }}</td>
                <td>{{ $p['brand_name'] ?? '' }}</td>
                <td>{{ number_format($p['cost_price'] ?? 0) }}</td>
                <td>{{ number_format(round(($p['cost_price'] ?? 0)*1.2 / 1000) * 1000) }}</td>
                <td>{{ $p['quantity_add'] ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
