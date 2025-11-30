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
                    @elseif($order->status === 'delivered')
                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã nhận</span>
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
                    <th>Số lượng</th>
                    <th><i class="bi bi-cash"></i> Đơn giá</th>
                    <th><i class="bi bi-currency-exchange"></i> Thành tiền</th>
                    <th>Đánh giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @php
                        // Tìm trong danh sách ratings của order xem có cái nào trùng product_id không
                        $existingRating = $order->ratings->where('product_id', $item->product_id)->first();
                    @endphp

                    <tr>
                        {{-- <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td> --}}
                        <td>
                            <div class="fw-bold">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</div>
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/'.$item->product->image) }}" width="40" class="rounded mt-1">
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                        {{-- Đánh giá sản phẩm --}}
                        <td class="text-center">
                            @if($order->status === 'delivered')
                                @if($existingRating)
                                    {{-- Đã đánh giá --}}
                                    <div class="text-warning small mb-1">
                                        @for($i=0; $i<$existingRating->rating; $i++) ⭐ @endfor
                                    </div>
                                    <button class="btn btn-sm btn-outline-success fw-bold"
                                            onclick="openReviewForm(this)"
                                            data-product-id="{{ $item->product_id }}"
                                            data-product-name="{{ $item->product->name ?? 'Sản phẩm' }}"
                                            data-rating="{{ $existingRating->rating }}"
                                            data-comment="{{ $existingRating->comment }}">
                                        <i class="bi bi-pencil-square"></i> Sửa đánh giá
                                    </button>
                                @else
                                    {{-- Chưa đánh giá: Hiện nút --}}
                                    <button class="btn btn-sm btn-outline-warning text-dark fw-bold"
                                            onclick="openReviewForm('{{ $item->product_id }}', '{{ $item->product->name ?? 'Sản phẩm' }}')">
                                        <i class="bi bi-pencil-square"></i> Viết đánh giá
                                    </button>
                                @endif
                            @elseif($order->status === 'cancelled')
                                <span class="text-muted small">Đã hủy</span>
                            @else
                                <span class="text-muted small">Chưa nhận hàng</span>
                            @endif
                        </td>
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

    {{-- Form đánh giá sản phẩm (ẩn ban đầu) --}}
    <div id="review-form-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-warning m-0"><i class="bi bi-star-fill"></i> Đánh giá sản phẩm</h4>
            <button type="button" class="btn-close" onclick="closeReviewForm()"></button>
        </div>

        <p class="mb-3">Bạn đang viết đánh giá cho: <strong id="review-product-name" class="text-primary fs-5">...</strong></p>

          <form action="{{ route('ratings.store') }}" method="POST">
            @csrf

            {{-- Input Ẩn --}}
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="product_id" id="review-product-id">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Chất lượng sản phẩm:</label>
                    <select name="rating" class="form-select text-warning fw-bold" required>
                        <option value="5" selected>⭐⭐⭐⭐⭐ - Tuyệt vời (5/5)</option>
                        <option value="4">⭐⭐⭐⭐ - Tốt (4/5)</option>
                        <option value="3">⭐⭐⭐ - Tạm được (3/5)</option>
                        <option value="2">⭐⭐ - Kém (2/5)</option>
                        <option value="1">⭐ - Rất tệ (1/5)</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-bold">Nhận xét của bạn:</label>
                    <textarea name="comment" class="form-control" rows="3" placeholder="Hãy chia sẻ cảm nhận về sản phẩm... (Tùy chọn)"></textarea>
                </div>
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-light border me-2" onclick="closeReviewForm()">Hủy</button>
                <button type="submit" class="btn btn-warning fw-bold px-4">Gửi đánh giá</button>
            </div>
        </form>
    </div>

    <script>
        function openReviewForm(button) {
            // 1. Lấy dữ liệu từ nút bấm (Data Attributes)
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const rating = button.getAttribute('data-rating');
            const comment = button.getAttribute('data-comment');

            // 2. Điền dữ liệu vào form
            document.getElementById('review-product-id').value = productId;
            document.getElementById('review-product-name').innerText = productName;
            document.getElementById('review-rating').value = rating; // Tự chọn số sao cũ
            document.getElementById('review-comment').value = comment; // Điền comment cũ

            // 3. Đổi tiêu đề form cho hợp lý
            const formTitle = document.getElementById('form-title');
            const submitBtn = document.getElementById('form-submit-btn');

            if (comment !== '' || rating !== '5') {
                // Nếu có comment hoặc rating khác mặc định -> Coi như là Sửa
                formTitle.innerHTML = '<i class="bi bi-pencil-square"></i> Cập nhật đánh giá';
                submitBtn.innerText = 'Cập nhật';
                // Đổi màu viền form sang xanh lá cho khác biệt
                document.getElementById('review-form-container').style.borderTopColor = '#198754';
            } else {
                // Viết mới
                formTitle.innerHTML = '<i class="bi bi-star-fill"></i> Viết đánh giá mới';
                submitBtn.innerText = 'Gửi đánh giá';
                document.getElementById('review-form-container').style.borderTopColor = '#ffc107';
            }

            // 4. Hiện form và cuộn xuống
            const formContainer = document.getElementById('review-form-container');
            formContainer.style.display = 'block';
            formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function closeReviewForm() {
            document.getElementById('review-form-container').style.display = 'none';
        }
    </script>
</body>
</html>
