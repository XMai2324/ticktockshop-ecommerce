<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #{{ $order->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .order-header {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .badge {
            font-size: 0.9rem;
            padding: 6px 10px;
        }
        .table th {
            background: #f1f3f5;
        }
        .order-summary {
            font-size: 1.1rem;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="container py-4">

    <div class="order-header">
        <h2><i class="bi bi-receipt-cutoff"></i> Đơn hàng #{{ $order->id }}</h2>
        <p class="mb-0"><i class="bi bi-calendar-event"></i> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-person-circle"></i> Thông tin người nhận</h5>
                    <p><strong>Tên:</strong> {{ $order->customer_name }}</p>
                    <p><strong><i class="bi bi-telephone"></i> Điện thoại:</strong> {{ $order->phone }}</p>
                    <p><strong><i class="bi bi-geo-alt"></i> Địa chỉ:</strong> {{ $order->address }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5><i class="bi bi-info-circle"></i> Trạng thái</h5>
                    @if($order->status === 'pending')
                        <span class="badge bg-warning"><i class="bi bi-hourglass-split"></i> Chờ xử lý</span>
                    @elseif($order->status === 'confirmed')
                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã xác nhận</span>
                    @elseif($order->status === 'cancelled')
                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Đã hủy</span>
                    @else
                        <span class="badge bg-secondary">{{ $order->status }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3"><i class="bi bi-box-seam"></i> Sản phẩm trong đơn</h4>
    <div class="table-responsive shadow-sm">
        <table class="table align-middle table-hover">
            <thead>
                <tr>
                    <th><i class="bi bi-bag"></i> Sản phẩm</th>
                    <th><i class="bi bi-123"></i> Số lượng</th>
                    <th><i class="bi bi-cash"></i> Đơn giá</th>
                    <th><i class="bi bi-currency-exchange"></i> Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->product->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="order-summary mt-3">
        <strong><i class="bi bi-wallet2"></i> Tổng cộng:</strong>
        <span class="text-danger fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} đ</span>
    </div>

    <a href="{{ route('orders.history') }}" class="btn btn-secondary mt-4">
        <i class="bi bi-arrow-left-circle"></i> Quay lại lịch sử đơn hàng
    </a>

</body>
</html>
