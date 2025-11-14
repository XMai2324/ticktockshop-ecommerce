<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả thanh toán VNPay</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .status { font-size: 24px; margin-bottom: 20px; }
        .success { color: green; }
        .failed { color: red; }
        .details { font-size: 18px; }
        a { display: inline-block; margin-top: 30px; text-decoration: none; color: #3a8bff; }
    </style>
</head>
<body>
    <h1>Kết quả thanh toán VNPay</h1>
    <p class="status {{ $success ? 'success' : 'failed' }}">{{ $message }}</p>
    <div class="details">
        <p><strong>Mã giao dịch:</strong> {{ $vnp_TxnRef }}</p>
        <p><strong>Số tiền:</strong> {{ number_format($vnp_Amount, 0, ',', '.') }} đ</p>
        <p><strong>Mã phản hồi:</strong> {{ $vnp_ResponseCode }}</p>
    </div>
    <a href="{{ url('http://127.0.0.1:8000/dashboard') }}">Quay về trang chủ</a>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lấy dữ liệu từ localStorage
        const checkoutInfo = JSON.parse(localStorage.getItem('checkout_info') || '{}');
    
        if (Object.keys(checkoutInfo).length === 0) {
            console.log("Không có dữ liệu checkout_info trong localStorage");
            return;
        }
    
        // Tạo phần hiển thị thông tin khách hàng
        const detailsDiv = document.querySelector('.details');
        const customerInfo = document.createElement('div');
        customerInfo.classList.add('details');
        customerInfo.innerHTML = `
            <h2>Thông tin khách hàng</h2>
            <p><strong>Họ tên:</strong> ${checkoutInfo.fullname || '---'}</p>
            <p><strong>Số điện thoại:</strong> ${checkoutInfo.phone || '---'}</p>
            <p><strong>Email:</strong> ${checkoutInfo.email || '---'}</p>
            <p><strong>Địa chỉ:</strong> ${checkoutInfo.address || '---'}</p>
            <p><strong>Quận/Huyện:</strong> ${checkoutInfo.district || '---'}</p>
            <p><strong>Tỉnh/Thành phố:</strong> ${checkoutInfo.province || '---'}</p>
        `;
    
        // Thêm vào trang
        detailsDiv.insertAdjacentElement('afterend', customerInfo);
    });
    </script>
    
</html>
