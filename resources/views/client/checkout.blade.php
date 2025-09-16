@extends('client.cart')

@section('title', 'Thanh toán')

@section('content')
    <div class="container" style="margin-top: 40px;">
        <h2 style="font-size: 28px; margin-bottom: 20px;">Giao diện trang thanh toán</h2>
        <p>Chị có thể chỉnh sửa layout, màu sắc, khung giỏ hàng tại đây.</p>

        <div class="checkout-preview" style="padding: 20px; background-color: #f9f9f9; border-radius: 12px;">
            <p><strong>Tạm tính:</strong> 0 VNĐ</p>
            <p><strong>Phí giao hàng:</strong> Chưa tính</p>
            <p><strong>Tổng cộng:</strong> 0 VNĐ</p>
        </div>
    </div>
@endsection