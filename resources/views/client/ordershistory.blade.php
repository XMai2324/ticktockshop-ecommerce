<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        table {
            border-radius: 12px;
            overflow: hidden;
        }
        thead {
            background: #343a40;
            color: white;
        }
        .badge {
            font-size: 0.85rem;
            padding: 6px 10px;
            border-radius: 8px;
        }
        .btn-sm {
            border-radius: 8px;
            padding: 5px 12px;
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body class="container py-5">

    <div class="card p-4">
        <h2 class="mb-4 text-center text-primary">📦 Lịch sử đơn hàng</h2>

        @if($orders->isEmpty())
            <div class="alert alert-info text-center">
                Bạn chưa có đơn hàng nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-danger fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                                <td>
                                    @if($order->status === 'pending')
                                        <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                    @elseif($order->status === 'confirmed')
                                        <span class="badge bg-success">Đã xác nhận</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge bg-danger">Đã hủy</span>
                                    @elseif($order->status === 'completed')
                                        <span class="badge bg-primary">Hoàn thành</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        Xem
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</body>
</html>
