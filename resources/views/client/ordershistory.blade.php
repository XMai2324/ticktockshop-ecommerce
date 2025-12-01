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
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái đơn hàng</th>
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
                                    @if(optional($order->payment)->method === 'cash')
                                        <span class="badge bg-warning text-dark">💵 Thanh toán khi nhận hàng</span>
                                    @elseif(optional($order->payment)->method === 'bank')
                                        <span class="badge bg-info text-dark">💳 Chuyển khoản ngân hàng</span>
                                    @else
                                        <span class="badge bg-secondary">Chưa tạo giao dịch</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">⏳ Chờ xử lý</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-primary">✅ Đã xác nhận</span>
                                            @break
                                        @case('shipping')
                                            <span class="badge bg-info text-dark">🚚 Đang giao</span>
                                            @break
                                        @case('delivered')
                                            <span class="badge bg-success">🎉 Đã nhận</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">❌ Đã hủy</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endswitch
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
