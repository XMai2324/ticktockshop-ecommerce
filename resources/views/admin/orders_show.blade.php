<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #{{ $order->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }
        .order-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            padding: 20px;
        }
        .order-header {
            border-bottom: 2px solid #eee;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }
        .order-info p {
            margin: 5px 0;
        }
        .table th {
            background: #f1f3f5;
        }
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 8px;
        }
    </style>
</head>
<body class="container py-4">

    <div class="order-card">
        <div class="order-header d-flex justify-content-between align-items-center">
            <h2>
                <i class="bi bi-receipt"></i> Đơn hàng #{{ $order->id }}
            </h2>
            <span class="badge 
                @if($order->status === 'pending') bg-warning text-dark
                @elseif($order->status === 'confirmed') bg-success
                @elseif($order->status === 'cancelled') bg-danger
                @else bg-secondary @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="order-info row">
            <div class="col-md-6">
                <p><i class="bi bi-person"></i> <strong>Khách hàng:</strong> {{ $order->customer_name }}</p>
                <p><i class="bi bi-telephone"></i> <strong>SĐT:</strong> {{ $order->phone }}</p>
            </div>
            <div class="col-md-6">
                <p><i class="bi bi-geo-alt"></i> <strong>Địa chỉ:</strong> {{ $order->address }}</p>
                <p><i class="bi bi-cash-stack"></i> <strong>Tổng tiền:</strong> 
                    {{ number_format($order->total_price, 0, ',', '.') }} đ
                </p>
            </div>
        </div>

        <h4 class="mt-4"><i class="bi bi-box-seam"></i> Sản phẩm trong đơn</h4>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-end">Đơn giá</th>
                    <th class="text-end">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/'.$item->product->image) }}" alt="Ảnh" class="product-img">
                            @else
                                <img src="https://via.placeholder.com/50" alt="Ảnh" class="product-img">
                            @endif
                            <span>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</span>
                        </div>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td>{{ number_format($item->product->price, 0, ',', '.') }} đ</td>
                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-3 text-end">
            <h5><strong>Tổng cộng:</strong> {{ number_format($order->total_price, 0, ',', '.') }} đ</h5>
        </div>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

</body>
</html>
