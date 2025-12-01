<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #{{ $order->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .order-header {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;
        }
        .badge { font-size: 0.9rem; padding: 6px 10px; }
        .table th { background: #f1f3f5; }
        .order-summary {
            font-size: 1.1rem; background: #fff; padding: 15px;
            border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        #review-form-container {
            background: #fff; border-radius: 12px; padding: 25px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            border-top: 5px solid #ffc107;
            display: none; margin-top: 30px;
            animation: slideUp 0.3s ease-out;
        }
        @keyframes slideUp { from {transform: translateY(20px); opacity: 0;} to {transform: translateY(0); opacity: 1;} }
    </style>
</head>
<body class="container py-4">

    {{-- HEADER --}}
    <div class="order-header">
        <h2><i class="bi bi-receipt-cutoff"></i> Đơn hàng #{{ $order->id }}</h2>
        <p class="mb-0"><i class="bi bi-calendar-event"></i> Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>

    {{-- INFO --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5><i class="bi bi-person-circle"></i> Người nhận</h5>
                    <p><strong>Tên:</strong> {{ $order->customer_name }}</p>
                    <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                    <p><strong>ĐC:</strong> {{ $order->address }}</p>
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

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- DANH SÁCH SẢN PHẨM --}}
    <h4 class="mb-3"><i class="bi bi-box-seam"></i> Sản phẩm trong đơn</h4>
    <div class="table-responsive shadow-sm bg-white rounded">
        <table class="table align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th class="text-center">SL</th>
                    <th class="text-end" style="text-align:center;">Đơn giá</th>
                    <th><i class="bi bi-currency-exchange"></i> Thành tiền</th>
                    <th class="text-center" style="width: 180px;">Đánh giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @php
                        $productName = optional($item->product)->name ?? 'Sản phẩm đã xóa';
                        $existingRating = $order->ratings->where('product_id', $item->product_id)->first();
                    @endphp

                    <tr>
                        <td>
                            <div class="fw-bold">{{ $productName }}</div>
                            @if(optional($item->product)->image)
                                <img src="{{ asset('storage/'.$item->product->image) }}" width="40" class="rounded mt-1">
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <!-- <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td> -->
                        <td>{{ number_format($item->product->price, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                        {{-- NÚT ĐÁNH GIÁ --}}
                        <td class="text-center">
                            @if($order->status === 'delivered' || $order->status === 'completed')
                                @if($existingRating)
                                    {{-- Nút Sửa --}}
                                    <div class="text-warning small mb-1">
                                        @for($i=0; $i < $existingRating->rating; $i++) <i class="bi bi-star-fill"></i> @endfor
                                    </div>
                                    <button class="btn btn-sm btn-outline-success fw-bold"
                                            onclick="openReviewForm(this)"
                                            data-product-id="{{ $item->product_id }}"
                                            data-product-name="{{ $productName }}"
                                            data-rating="{{ $existingRating->rating }}"
                                            data-comment="{{ $existingRating->comment }}">
                                        <i class="bi bi-pencil-square"></i> Sửa đánh giá
                                    </button>
                                @else
                                    {{-- Nút Viết Mới --}}
                                    <button class="btn btn-sm btn-outline-warning text-dark fw-bold"
                                            onclick="openReviewForm(this)"
                                            data-product-id="{{ $item->product_id }}"
                                            data-product-name="{{ $productName }}"
                                            data-rating="5"
                                            data-comment="">
                                        <i class="bi bi-star"></i> Viết đánh giá
                                    </button>
                                @endif
                            @else
                                <span class="text-muted small">--</span>
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

    {{-- FORM ĐÁNH GIÁ --}}
    <div id="review-form-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-warning m-0" id="form-title"></h4>
            <button type="button" class="btn-close" onclick="closeReviewForm()"></button>
        </div>

        <p class="mb-3">Đánh giá cho: <strong id="review-product-name" class="text-primary fs-5">...</strong></p>

        <form action="{{ route('ratings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="product_id" id="review-product-id">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Chất lượng:</label>
                    <select name="rating" id="review-rating" class="form-select text-warning fw-bold">
                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                        <option value="3">⭐⭐⭐ (3/5)</option>
                        <option value="2">⭐⭐ (2/5)</option>
                        <option value="1">⭐ (1/5)</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-bold">Nhận xét:</label>

                    {{-- Textarea để trống value, placeholder sẽ được JS set --}}
                    <textarea name="comment" id="review-comment" class="form-control" rows="3"></textarea>

                </div>
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-light border me-2" onclick="closeReviewForm()">Hủy</button>
                <button type="submit" class="btn btn-warning fw-bold px-4" id="form-submit-btn">Gửi</button>
            </div>
        </form>
    </div>

    <script>
        function openReviewForm(button) {
            // Lấy dữ liệu
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const rating = button.getAttribute('data-rating');
            const comment = button.getAttribute('data-comment');

            // Điền ID và Tên SP
            document.getElementById('review-product-id').value = productId;
            const nameElement = document.getElementById('review-product-name');
            nameElement.textContent = (productName && productName.trim() !== "") ? productName : "Sản phẩm này";

            // Điền Rating
            document.getElementById('review-rating').value = rating;

            // Xử lý Comment và Placeholder
            const commentInput = document.getElementById('review-comment');

            // Xóa dữ liệu cũ trong ô nhập liệu
            commentInput.value = '';

            if (comment && comment.trim() !== '') {
                // Nếu có comment cũ: Đặt làm Placeholder
                commentInput.setAttribute('placeholder', comment);
                // Nếu bạn muốn người dùng vẫn sửa trên nội dung cũ thì bỏ comment dòng dưới:
                commentInput.value = comment;
            } else {
                // Nếu chưa có: Đặt placeholder mặc định
                commentInput.setAttribute('placeholder', 'Hãy chia sẻ cảm nhận về sản phẩm...');
            }

            // Giao diện
            const formContainer = document.getElementById('review-form-container');
            const formTitle = document.getElementById('form-title');
            const submitBtn = document.getElementById('form-submit-btn');

            if (comment !== '' || rating !== '5') {
                formTitle.innerHTML = '<i class="bi bi-pencil-square"></i> Cập nhật đánh giá';
                submitBtn.innerText = 'Cập nhật';
                formContainer.style.borderTopColor = '#198754';
            } else {
                formTitle.innerHTML = '<i class="bi bi-star-fill"></i> Viết đánh giá mới';
                submitBtn.innerText = 'Gửi đánh giá';
                formContainer.style.borderTopColor = '#ffc107';
            }

            formContainer.style.display = 'block';
            formContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function closeReviewForm() {
            document.getElementById('review-form-container').style.display = 'none';
        }
    </script>

</body>
</html>
