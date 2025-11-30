@extends('admin.dashboard')

@section('content')
    <div class="dashboard-wrapper">

        {{-- PHẦN HEADER ĐỨNG YÊN --}}
        <div class="stats-header-fixed">

            <a href="#sec-products" class="stat-card-link">
                <div class="stat-card border-blue">
                    <div class="stat-info">
                        <h3>Thống kê sản phẩm</h3>
                        <p>{{ number_format($total_products) }}</p>
                    </div>
                    <div class="stat-icon">📦</div>
                </div>
            </a>

            <a href="#sec-revenue" class="stat-card-link">
                <div class="stat-card border-green">
                    <div class="stat-info">
                        <h3>Doanh thu</h3>
                        <p>{{ number_format($total_revenue) }} <small>đ</small></p>
                    </div>
                    <div class="stat-icon">💰</div>
                </div>
            </a>

            <a href="#sec-ratings" class="stat-card-link">
                <div class="stat-card border-yellow">
                    <div class="stat-info">
                        <h3>Đánh giá TB</h3>
                        <p>{{ $avg_rating }} / 5 ⭐
                            <span style="font-size: 12px; color: #888; font-style: italic;">
                                ({{ $total_ratings }} lượt)
                            </span>
                        </p>
                    </div>
                    <div class="stat-icon">💬</div>
                </div>
            </a>

            <a href="#sec-orders" class="stat-card-link">
                <div class="stat-card border-cyan">
                    <div class="stat-info">
                        <h3>Tổng đơn hàng</h3>
                        <p>{{ number_format($total_orders) }}</p>
                    </div>
                    <div class="stat-icon">🛒</div>
                </div>
            </a>

        </div>
        {{-- PHẦN NỘI DUNG CUỘN --}}
        <div class="stats-content-scroll">

            {{-- SECTION: THỐNG KÊ SẢN PHẨM --}}
            <div id="sec-products" style="margin-top: 30px;">

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="margin: 0; color: #333; font-size: 24px;">📦 Phân tích Kho hàng</h2>
                    <span class="text-muted">Cập nhật: {{ now()->format('H:i d/m/Y') }}</span>
                </div>

                <div class="summary-grid">
                    <div class="summary-box" style="border-left-color: var(--primary);">
                        <div>
                            <div class="sb-title" style="color: var(--primary)">Tổng sản phẩm</div>
                            <div class="sb-value">{{ $total_products }}</div>
                        </div>
                        <i class="fas fa-cubes" style="color: #ddd; font-size: 24px;"></i>
                    </div>

                    <div class="summary-box" style="border-left-color: var(--warning);">
                        <div>
                            <div class="sb-title" style="color: var(--warning)">Sắp hết hàng</div>
                            <div class="sb-value">{{ $low_stock_products->count() }}</div>
                        </div>
                        <i class="fas fa-exclamation-triangle" style="color: #ddd; font-size: 24px;"></i>
                    </div>

                    <div class="summary-box" style="border-left-color: var(--danger);">
                        <div>
                            <div class="sb-title" style="color: var(--danger)">Tồn đọng</div>
                            <div class="sb-value">{{ $unsold_products->count() }} mặt hàng</div>
                        </div>
                        <i class="fas fa-ghost" style="color: #ddd; font-size: 24px;"></i>
                    </div>

                    <div class="summary-box" style="border-left-color: var(--success);">
                        <div>
                            <div class="sb-title" style="color: var(--success)">Đã bán ra</div>
                            <div class="sb-value">{{ number_format(\App\Models\OrderItem::sum('quantity')) }} sản phẩm</div>
                        </div>
                        <i class="fas fa-shopping-basket" style="color: #ddd; font-size: 24px;"></i>
                    </div>
                </div>

                <div class="dashboard-grid">

                    <div class="custom-card">
                        <div class="card-header">
                            <h3 class="card-title" style="color: var(--primary)">
                                <i class="fas fa-crown"></i> Top Bán Chạy
                            </h3>
                        </div>
                        {{-- Biểu đồ Cột Ngang --}}
                        <div style="padding: 20px; border-bottom: 1px solid var(--border);">
                            <div style="height: 400px; width: 100%;">
                                <canvas id="topSellingChart"></canvas>
                            </div>
                        </div>
                        {{-- Bảng Dữ liệu Chi tiết --}}
                        <div class="table-wrapper">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px">#</th>
                                        <th>Sản phẩm</th>
                                        <th class="text-right">SL Bán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_selling_products as $index => $p)
                                        <tr>
                                            <td class="text-center">
                                                <div
                                                    style="
                                    width: 24px; height: 24px; border-radius: 50%;
                                    background: {{ $index < 3 ? '#fef3c7' : '#f3f4f6' }};
                                    color: {{ $index < 3 ? '#d97706' : '#6b7280' }};
                                    display: flex; align-items: center; justify-content: center;
                                    font-weight: bold; margin: 0 auto;
                                ">
                                                    {{ $index + 1 }}</div>
                                            </td>
                                            <td>
                                                <div class="item-flex">
                                                    @if ($p->image)
                                                        <img src="{{ asset('storage/' . $p->image) }}" class="item-thumb">
                                                    @endif
                                                    <div class="item-info">
                                                        <span class="item-name name-lg"
                                                            title="{{ $p->name }}">{{ $p->name }}</span>
                                                        <div class="tags-group">
                                                            @if ($p->brand)
                                                                <span class="tag tag-brand">{{ $p->brand->name }}</span>
                                                            @endif
                                                            @if ($p->category)
                                                                <span class="tag tag-cat">{{ $p->category->name }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <span class="badge badge-success">{{ $p->total_sold }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Phần Sắp hết hàng & Tồn đọng --}}
                    <div class="custom-card border-top-warning">
                        <div class="card-header">
                            <h3 class="card-title" style="color: var(--warning)">
                                <i class="fas fa-bell"></i> Sắp hết
                            </h3>
                        </div>
                        <div class="table-wrapper">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th style="width: 75%">Sản phẩm</th>
                                        <th class="text-center" style="width: 25%">Tồn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($low_stock_products as $ls)
                                        <tr>
                                            <td>
                                                <span class="item-name name-sm" style="width: 120px"
                                                    title="{{ $ls->name }}">{{ $ls->name }}</span>
                                                <div class="tags-group">
                                                    <span class="tag tag-brand">{{ $ls->brand->name ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span style="font-weight: 800; color: var(--danger); font-size: 16px;">
                                                    {{ $ls->quantity }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Kho ổn định</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="custom-card border-top-danger">
                        <div class="card-header">
                            <h3 class="card-title" style="color: var(--danger)">
                                <i class="fas fa-ghost"></i> Tồn đọng
                            </h3>
                            <span class="badge badge-danger"
                                style="font-size: 10px">{{ $unsold_products->count() }}</span>
                        </div>
                        <div class="table-wrapper">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th style="width: 60%">Sản phẩm</th>
                                        <th class="text-center" style="width: 15%">SL</th>
                                        <th class="text-right" style="width: 25%">Lưu kho</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($unsold_products as $us)
                                        <tr>
                                            <td>
                                                <div class="item-flex">
                                                    <div class="item-info">
                                                        <span class="item-name name-lg" style="width: 110px"
                                                            title="{{ $us->name }}">{{ $us->name }}</span>
                                                        <div class="tags-group">
                                                            <span
                                                                class="tag tag-brand">{{ $us->brand->name ?? '-' }}</span>
                                                            <span
                                                                class="tag tag-cat">{{ $us->category->name ?? '-' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    style="background:#f3f4f6; color:#666; padding:2px 6px; border-radius:4px; font-weight:bold; font-size:11px;">
                                                    {{ $us->quantity }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <span
                                                    style="display:block; font-weight:600; font-size:12px;">{{ $us->created_at->format('d/m/y') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Không có hàng tồn!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <a href="#" class="btn-action">
                            <i class="fas fa-fire-alt"></i> Xả hàng ngay
                        </a>
                    </div>

                </div>
            </div>

            {{-- Phần Biểu đồ Doanh thu --}}
            <div id="sec-revenue" class="detail-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title mb-0 border-0 p-0">💰 Biểu đồ Doanh thu</h2>

                    <form method="GET" action="{{ route('admin.statistical') }}#sec-revenue" id="filterForm">
                        <select name="filter" class="form-control custom-select"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Theo 30 ngày qua</option>
                            <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Theo tháng (Năm nay)
                            </option>
                            <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Theo các năm</option>
                        </select>
                    </form>
                </div>

                <div style="height: 400px; width: 100%;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Phần Phân tích Đánh giá & Phản hồi --}}
            <div id="sec-ratings" class="detail-section">
                <h2 class="section-title">⭐ Phân tích Đánh giá & Phản hồi</h2>
                <div class="row">
                    <div class="col-xl-4 col-lg-12 mb-4">
                        {{-- Biểu đồ Tròn: Tỉ lệ sao --}}
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Phân bổ số sao</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 250px;">
                                    <canvas id="ratingPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                        {{-- Biểu đồ Cột: Top sản phẩm được review nhiều nhất --}}
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Top 5 Sản phẩm nhiều phản hồi nhất</h6>
                            </div>
                            <div class="card-body">
                                <div style="height: 300px;">
                                    <canvas id="ratingBarChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BẢNG DỮ LIỆU & BỘ LỌC --}}
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            {{-- HEADER CARD --}}
                            <div class="card-header py-3 bg-white border-bottom-0">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-trophy mr-1 text-warning"></i> {{-- Thêm icon cúp vàng --}}
                                    {{ $filter_star == 'all' ? 'Bảng Xếp Hạng Sản Phẩm' : 'Top SP ' . $filter_star . ' Sao' }}
                                </h6>
                            </div>

                            <div class="card-body p-0">

                                {{-- FORM FILTER --}}
                                <div class="px-3 pb-3">
                                    <div class="bg-light rounded p-2"> {{-- Thêm nền xám nhẹ cho vùng form --}}
                                        <form method="GET" action="{{ route('admin.statistical') }}#sec-ratings"
                                            id="ratingFilterForm">
                                            @if (request('filter'))
                                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                                            @endif

                                            <div class="row">
                                                <div class="col-6 pr-1">
                                                    <small class="text-muted font-weight-bold d-block mb-1">Lọc
                                                        sao:</small>
                                                    <select name="rating_star"
                                                        class="custom-select custom-select-sm shadow-none"
                                                        onchange="this.form.submit()">
                                                        <option value="all"
                                                            {{ $filter_star == 'all' ? 'selected' : '' }}>Tất cả</option>
                                                        <option value="5"
                                                            {{ $filter_star == '5' ? 'selected' : '' }}>
                                                            5 Sao (Tuyệt vời)</option>
                                                        <option value="4"
                                                            {{ $filter_star == '4' ? 'selected' : '' }}>
                                                            4 Sao (Tốt)</option>
                                                        <option value="3"
                                                            {{ $filter_star == '3' ? 'selected' : '' }}>
                                                            3 Sao (TB)</option>
                                                        <option value="2"
                                                            {{ $filter_star == '2' ? 'selected' : '' }}>
                                                            2 Sao (Tệ)</option>
                                                        <option value="1"
                                                            {{ $filter_star == '1' ? 'selected' : '' }}>
                                                            1 Sao (Rất tệ)</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 pl-1">
                                                    <small class="text-muted font-weight-bold d-block mb-1">Thứ tự:</small>
                                                    <select name="rating_sort"
                                                        class="custom-select custom-select-sm shadow-none"
                                                        onchange="this.form.submit()">
                                                        <option value="desc"
                                                            {{ $filter_sort == 'desc' ? 'selected' : '' }}>Cao nhất ⬇
                                                        </option>
                                                        <option value="asc"
                                                            {{ $filter_sort == 'asc' ? 'selected' : '' }}>Thấp nhất ⬆
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- BẢNG DỮ LIỆU --}}
                                <div class="rating-table-container">
                                    <table class="table table-hover mb-0 table-sticky-header">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center border-top-0 pl-3" style="width: 50px;">#</th>
                                                <th class="border-top-0" style="width: auto;">Sản phẩm</th>
                                                <th class="text-center border-top-0 pr-3" style="width: 80px;">Lượt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($table_products as $index => $p)
                                                <tr>
                                                    {{-- Cột Thứ hạng (Rank) --}}
                                                    <td class="text-center align-middle pl-3">
                                                        <span class="rank-badge rank-{{ $index + 1 }}">
                                                            {{ $index + 1 }}
                                                        </span>
                                                    </td>

                                                    {{-- Cột Tên sản phẩm --}}
                                                    <td class="align-middle py-3">
                                                        <span class="product-name-truncate font-weight-bold text-dark"
                                                            title="{{ $p->name }}">
                                                            {{ $p->name }}
                                                        </span>
                                                    </td>

                                                    {{-- Cột Số lượng --}}
                                                    <td class="text-center align-middle pr-3">
                                                        <span class="count-pill">
                                                            {{ $p->ratings_count }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted py-5">
                                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png"
                                                            width="60" class="mb-2 opacity-50"><br>
                                                        <span class="small">Không tìm thấy dữ liệu phù hợp</span>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Danh sách sản phẩm chưa có đánh giá --}}
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div
                                class="card-header py-3 bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-danger">
                                    <i class="fas fa-comment-slash mr-1"></i> Chưa có đánh giá
                                </h6>
                                <span class="badge badge-danger badge-pill">{{ count($unrated_products) }}</span>
                            </div>

                            <div class="card-body p-0">
                                <div class="rating-table-container">
                                    <table class="table table-hover mb-0">
                                        <thead class="bg-light text-danger">
                                            <tr>
                                                <th class="pl-3 border-top-0">Sản phẩm</th>
                                                <th class="text-right border-top-0 pr-3">Ngày tạo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($unrated_products as $u)
                                                <tr>
                                                    <td class="align-middle pl-3 py-2">
                                                        <div class="d-flex align-items-center">
                                                            @if ($u->image)
                                                                <img src="{{ asset('storage/' . $u->image) }}"
                                                                    class="rounded mr-2 border" width="30"
                                                                    height="30">
                                                            @endif
                                                            <span class="product-name-truncate text-dark"
                                                                style="max-width: 140px;" title="{{ $u->name }}">
                                                                {{ $u->name }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-right pr-3 text-muted small">
                                                        {{ $u->created_at->format('d/m/Y') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted py-5 small">Tuyệt
                                                        vời! Các sản phẩm đều được đánh giá.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white text-center border-top-0 pb-3 pt-0">
                                <a href="#" class="btn btn-sm btn-light text-danger font-weight-bold w-100">Xem tất
                                    cả <i class="fas fa-arrow-right ml-1"></i></a>
                            </div>
                        </div>
                    </div>
                    {{-- Thêm khoảng trắng dưới cùng để phần cuối có thể cuộn lên cao --}}
                    <div style="height: 100px;"></div>
                </div>

            </div>

            {{-- Thống kê đơn hàng --}}
            <div id="sec-orders" style="margin-top: 40px; margin-bottom: 50px;">
                <div style="margin-bottom: 20px;">
                    <h2 style="margin: 0; color: #333; font-size: 24px;">🧾 Phân tích Đơn hàng</h2>
                </div>

                <div class="dashboard-grid" style="grid-template-columns: 3fr 2fr;">

                    <div class="custom-card border-top-success" style="border-top: 4px solid #1cc88a;">
                        <div class="card-header">
                            <h3 class="card-title" style="color: #1cc88a">
                                <i class="fas fa-trophy"></i> Top 20 Đơn Giá Trị Cao Nhất
                            </h3>
                        </div>

                        <div class="table-wrapper">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px">#</th>
                                        <th>Khách hàng</th>
                                        <th class="text-center">Ngày đặt</th>
                                        <th class="text-right">Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($top_value_orders as $index => $order)
                                        <tr>
                                            <td class="text-center">
                                                <span style="font-weight:bold; color:#888">#{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <span style="font-weight:600; display:block; color:#333">
                                                        {{ $order->customer_name ?? ($order->user->name ?? 'Khách lẻ') }}
                                                    </span>
                                                    <span style="font-size:11px; color:#999">Mã đơn:
                                                        #{{ $order->id }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    style="font-size:12px; color:#555">{{ $order->created_at->format('d/m/Y') }}</span>
                                            </td>
                                            <td class="text-right">
                                                <span class="badge badge-success" style="font-size:12px;">
                                                    {{ number_format($order->total_price) }} ₫
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 24px;">

                        <div class="custom-card">
                            <div class="card-header">
                                <h3 class="card-title" style="color: #5a5c69">
                                    <i class="fas fa-chart-pie"></i> Trạng thái đơn
                                </h3>
                            </div>
                            <div
                                style="padding: 15px; height: 220px; display:flex; align-items:center; justify-content:center;">
                                <canvas id="orderStatusChart"></canvas>
                            </div>
                        </div>

                        <div class="custom-card border-top-warning" style="border-top: 4px solid #36b9cc;">
                            <div class="card-header">
                                <h3 class="card-title" style="color: #36b9cc">
                                    <i class="fas fa-shopping-cart"></i> Top 5 Đơn Nhiều Sản Phẩm Nhất
                                </h3>
                            </div>
                            <div class="table-wrapper">
                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>Khách hàng</th>
                                            <th class="text-center">SL</th>
                                            <th class="text-right">Giá trị</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($most_products_orders as $order)
                                            <tr>
                                                <td>
                                                    <span
                                                        style="font-weight:600; font-size:13px; color:#333; display:block; max-width:120px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                        {{ $order->customer_name ?? ($order->user->name ?? 'Khách lẻ') }}
                                                    </span>
                                                    <span style="font-size:10px; color:#999">mã đơn: #{{ $order->id }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        style="background:#e0f2fe; color:#0369a1; padding:3px 8px; border-radius:10px; font-weight:bold; font-size:11px;">
                                                        {{ $order->order_items_sum_quantity }}
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <span
                                                        style="font-size:12px; color:#555">{{ number_format($order->total_price) }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{-- Script ChartJS giữ nguyên --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('revenueChart').getContext('2d');

                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(78, 115, 223, 0.5)');
                gradient.addColorStop(1, 'rgba(78, 115, 223, 0.0)');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chart_labels), // Dữ liệu nhãn (Ngày 1/1, Tháng 1, Năm 2024...)
                        datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: @json($chart_data), // Dữ liệu tiền
                            borderColor: '#4e73df',
                            backgroundColor: gradient,
                            borderWidth: 2,
                            pointBackgroundColor: '#fff',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(context.raw);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString('vi-VN') + ' đ';
                                    }
                                }
                            }
                        }
                    }
                });

                // --- 1. BIỂU ĐỒ TRÒN (PIE CHART) ---
                var ctxPie = document.getElementById("ratingPieChart");
                var ratingPieChart = new Chart(ctxPie, {
                    type: 'doughnut', // Hoặc 'pie'
                    data: {
                        labels: ["1 Sao", "2 Sao", "3 Sao", "4 Sao", "5 Sao"],
                        datasets: [{
                            data: @json($pie_data),
                            backgroundColor: ['#e74a3b', '#e09d3b', '#f6c23e', '#36b9cc',
                                '#1cc88a'
                            ], // Đỏ, Cam, Vàng, Xanh dương, Xanh lá
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    },
                });

                // --- 2. BIỂU ĐỒ CỘT (BAR CHART) ---
                var ctxBar = document.getElementById("ratingBarChart");
                var ratingBarChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: @json($bar_labels),
                        datasets: [{
                            label: "Số lượt đánh giá",
                            backgroundColor: "#4e73df",
                            hoverBackgroundColor: "#2e59d9",
                            borderColor: "#4e73df",
                            data: @json($bar_data),
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        indexAxis: 'y', // 'y' để xoay ngang biểu đồ cột (giúp đọc tên sản phẩm dài dễ hơn)
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
                // --- BIỂU ĐỒ TOP SẢN PHẨM BÁN CHẠY ---
                var ctxTopSelling = document.getElementById("topSellingChart");
                if (ctxTopSelling) {
                    // Lấy dữ liệu từ PHP
                    var topSellingNames = @json($top_selling_products->pluck('name'));
                    var topSellingQty = @json($top_selling_products->pluck('total_sold'));

                    // Rút gọn tên nếu quá dài
                    var shortNames = topSellingNames.map(name => name.length > 20 ? name.substring(0, 20) + '...' : name);

                    var myTopSellingChart = new Chart(ctxTopSelling.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: shortNames,
                            datasets: [{
                                label: "Số lượng bán",
                                backgroundColor: "#4e73df",
                                hoverBackgroundColor: "#2e59d9",
                                borderColor: "#4e73df",
                                data: topSellingQty,
                                barThickness: 30, // Độ dày cột
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            indexAxis: 'y', // Xoay ngang biểu đồ cho dễ đọc tên SP
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 0,
                                    bottom: 0
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 6
                                    },
                                },
                                y: {
                                    grid: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    },
                                    ticks: {
                                        padding: 10
                                    }
                                },
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        // Hiển thị tên đầy đủ trong tooltip
                                        title: function(tooltipItems) {
                                            return topSellingNames[tooltipItems[0].dataIndex];
                                        }
                                    }
                                }
                            },
                        },
                    });
                }

                // --- BIỂU ĐỒ TRẠNG THÁI ĐƠN HÀNG ---

                // --- BIỂU ĐỒ TRÒN TRẠNG THÁI ĐƠN HÀNG ---
                const ctxOrderStatus = document.getElementById("orderStatusChart");

                if (ctxOrderStatus) {
                    new Chart(ctxOrderStatus.getContext('2d'), {
                        type: 'doughnut', // Hoặc 'pie'
                        data: {
                            labels: @json($status_chart_labels),
                            datasets: [{
                                data: @json($status_chart_data),
                                backgroundColor: [
                                    '#f6c23e', // Vàng (Chờ xử lý)
                                    '#4e73df', // Xanh dương (Xác nhận)
                                    '#36b9cc', // Xanh ngọc (Giao hàng)
                                    '#e74a3b', // Đỏ (Hủy)
                                    '#858796' // Xám (Khác)
                                ],
                                borderWidth: 0, // Bỏ viền trắng giữa các miếng
                                hoverOffset: 4
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right', // Chú thích nằm bên phải cho gọn
                                    labels: {
                                        boxWidth: 12,
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            layout: {
                                padding: 0
                            }
                        },
                    });
                }
            </script>
        @endsection
