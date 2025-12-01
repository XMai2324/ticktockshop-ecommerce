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
                                                            title="{{ $p->name }}">{{ $p->name }}
                                                            ({{ $p->id }})</span>
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
                                        <th class="text-center" style="width: 25%">Tồn kho</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($low_stock_products as $ls)
                                        <tr>
                                            <td>
                                                <div class="item-flex">
                                                    <div class="item-info">
                                                        <span class="item-name name-lg" style="width: 110px"
                                                            title="{{ $ls->name }}">{{ $ls->name }}({{ $ls->id }})</span>
                                                        <div class="tags-group">
                                                            <span
                                                                class="tag tag-brand">{{ $ls->brand->name ?? '-' }}</span>
                                                            <span
                                                                class="tag tag-cat">{{ $ls->category->name ?? '-' }}</span>
                                                        </div>
                                                    </div>
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
                        <span
                            style="font-size: 12px; font-weight:600; color: var(--warning); text-align:center; margin-top: 10px">Ưu
                            tiên nhập các sản phẩm này!!!</span>
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
                                                            title="{{ $us->name }}">{{ $us->name }}({{ $us->id }})</span>
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
                        <span style="font-size: 12px; font-weight:600; color: red; text-align:center; margin-top: 10px">Nên
                            đẩy các sản phẩm này lên trang dầu để có thể khách hàng tiếp cận</span>
                    </div>

                </div>
            </div>

            {{-- Phần Biểu đồ Doanh thu --}}
            <div class="card-modern mb-4" id="sec-revenue">
                <div class="card-header-modern">
                    <h2 class="card-title-text text-success" style="font-size: "><i class="fas fa-chart-line mr-2"></i>
                        Phân tích Doanh thu
                    </h2>

                    {{-- Form lọc thời gian cho biểu đồ đường --}}
                    <form method="GET" action="{{ route('admin.statistical') }}#sec-revenue" class="d-flex">
                        @if (request('order_date_filter'))
                            <input type="hidden" name="order_date_filter" value="{{ request('order_date_filter') }}">
                        @endif
                        <select name="filter" class="custom-select custom-select-sm" onchange="this.form.submit()"
                            style="border-radius: 20px;">
                            <option value="day" {{ request('filter') == 'day' ? 'selected' : '' }}>30 ngày qua</option>
                            <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Theo tháng (Năm
                                nay)</option>
                            <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>Theo năm</option>
                        </select>
                    </form>
                </div>

                <div class="card-body">

                    {{-- 1. BIỂU ĐỒ ĐƯỜNG (TỔNG DOANH THU) --}}
                    <div class="mb-4 pb-4 border-bottom">
                        <h6 class="text-muted small font-weight-bold mb-3 text-uppercase">1. Xu hướng doanh thu</h6>
                        <div style="height: 300px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    {{-- 2. HAI BIỂU ĐỒ TRÒN (BRAND & CATEGORY) --}}
                    <div class="row">
                        {{-- Biểu đồ Brand --}}
                        <div class="col-md-6 border-right">
                            <h6 class="text-muted small font-weight-bold mb-3 text-uppercase text-center">2. Tỉ trọng theo
                                Thương hiệu</h6>
                            <div style="height: 250px; position: relative;">
                                <canvas id="brandRevenueChart"></canvas>
                            </div>
                        </div>

                        {{-- Biểu đồ Category --}}
                        <div class="col-md-6">
                            <h6 class="text-muted small font-weight-bold mb-3 text-uppercase text-center">3. Tỉ trọng theo
                                Danh mục</h6>
                            <div style="height: 250px; position: relative;">
                                <canvas id="categoryRevenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Phần Phân tích Đánh giá & Phản hồi --}}
            <div id="sec-ratings" class="detail-section">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h2 class="section-title m-0">⭐ Phân tích Đánh giá & Phản hồi</h2>
                </div>

                {{-- LAYOUT LƯỚI 2x2 --}}
                <div class="grid-2x2">

                    {{-- Ô 1: BIỂU ĐỒ TRÒN (PHÂN BỔ SAO) --}}
                    <div class="custom-card">
                        <div class="card-header">
                            <h3 class="card-title" style="color: #f6c23e;">
                                <i class="fas fa-chart-pie"></i> Phân bổ số sao
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="chart-container">
                                <canvas id="ratingPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                    {{-- Ô 2: BIỂU ĐỒ CỘT (TOP NHIỀU REVIEW NHẤT) --}}
                    <div class="custom-card">
                        <div class="card-header">
                            <h3 class="card-title" style="color: #4e73df;">
                                <i class="fas fa-chart-bar"></i> Top 5 Sản phẩm sôi nổi nhất
                            </h3>
                        </div>
                        <div class="card-body p-4">
                            <div class="chart-container">
                                <canvas id="ratingBarChart"></canvas>
                            </div>
                        </div>
                    </div>

                {{-- BẢNG DỮ LIỆU & BỘ LỌC --}}
                <div class="custom-card h-100">
                    <div class="card-header">
                        <h3 class="card-title" style="color: #1cc88a;">
                            <i class="fas fa-trophy"></i> Bảng Xếp Hạng Chất Lượng
                        </h3>

                        <form method="GET" action="{{ route('admin.statistical') }}#sec-ratings" class="d-flex align-items-center gap-2">
                            @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif

                            <select name="rating_star" class="custom-select custom-select-sm" style="width:auto; border-radius:15px;" onchange="this.form.submit()">
                                <option value="all" {{ $filter_star == 'all' ? 'selected' : '' }}>Tất cả sao</option>
                                <option value="5" {{ $filter_star == '5' ? 'selected' : '' }}>5 Sao</option>
                                <option value="4" {{ $filter_star == '4' ? 'selected' : '' }}>4 Sao</option>
                                <option value="3" {{ $filter_star == '3' ? 'selected' : '' }}>3 Sao</option>
                                <option value="2" {{ $filter_star == '2' ? 'selected' : '' }}>2 Sao</option>
                                <option value="1" {{ $filter_star == '1' ? 'selected' : '' }}>1 Sao</option>
                            </select>

                            <select name="rating_sort" class="custom-select custom-select-sm" style="width:auto; border-radius:15px;" onchange="this.form.submit()">
                                <option value="desc" {{ $filter_sort == 'desc' ? 'selected' : '' }}>Cao nhất ⬇</option>
                                <option value="asc" {{ $filter_sort == 'asc' ? 'selected' : '' }}>Thấp nhất ⬆</option>
                            </select>
                        </form>
                    </div>

                    <div class="table-wrapper" style="height: 400px;"> {{-- Tăng chiều cao bảng --}}
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%;">#</th>
                                    <th style="width: 65%;">Sản phẩm</th>
                                    <th class="text-center" style="width: 20%;">Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($table_products as $index => $p)
                                <tr>
                                    <td class="text-center">
                                        <div class="rank-badge {{ $index < 3 ? 'rank-'.($index+1) : 'rank-other' }}">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="item-flex">
                                            @if($p->image) <img src="{{ asset('storage/'.$p->image) }}" class="item-thumb"> @endif
                                            <div class="item-info">
                                                <span class="item-name" title="{{$p->name}}">{{$p->name}}({{$p->id}})</span>
                                                <div class="tags-group">
                                                    @if($p->brand) <span class="tag tag-brand">{{ $p->brand->name }}</span> @endif
                                                    @if($p->brand) <span class="tag tag-cat">{{ $p->category->name }}</span> @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $p->ratings_count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted py-5">Không có dữ liệu</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Danh sách sản phẩm chưa có đánh giá --}}
                <div class="custom-card border-top-danger h-100">
                    <div class="card-header">
                        <h3 class="card-title" style="color: #e74a3b;">
                            <i class="fas fa-comment-slash"></i> Chưa đánh giá
                        </h3>
                        <span class="badge badge-danger">{{ count($unrated_products) }} SP</span>
                    </div>

                    <div class="table-wrapper" style="height: 350px;">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 50%;">Sản phẩm</th>
                                    <th style="width: 15%;">Đơn hàng</th>
                                    <th class="text-right">Ngày nhập</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unrated_products as $u)
                                <tr>
                                    <td>
                                        <div class="item-flex">
                                            @if($p->image) <img src="{{ asset('storage/'.$u->image) }}" class="item-thumb"> @endif
                                            <div class="item-info">
                                                <span class="item-name" title="{{$u->name}}">{{$u->product->name}}({{$u->product->id}})</span>
                                                <div class="tags-group">
                                                    @if($u->product->brand) <span class="tag tag-brand">{{ $u->product->brand->name }}</span> @endif
                                                    @if($u->product->category) <span class="tag tag-cat">{{ $u->product->category->name }}</span> @endif
                                                </div>
                                                <span class="item-price text-muted">
                                                    {{ number_format($u->product->price) }}đ
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.show', $u->order_id) }}" class="badge" style="background: #e2e8f0; color: #4a5568; text-decoration: none;">
                                            #{{ $u->order_id }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <span style="font-weight: 600; font-size: 12px; display: block; color: #2d3748;">
                                            {{ $u->order->updated_at->format('d/m') }}
                                        </span>
                                        <span class="time-ago text-danger" style="font-size: 10px;">
                                            {{ $u->order->updated_at->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="text-success">
                                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                            Tất cả sản phẩm đều đã có đánh giá!
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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

            <!-- MAIN GRID -->
            <div class="dashboard-grid" style="grid-template-columns: 3fr 2fr;">
                <div style="display: flex; flex-direction: column; gap: 24px;">

                    {{-- [MỚI] 1. BIỂU ĐỒ XU HƯỚNG ĐẶT HÀNG --}}
                    <div class="custom-card">
                        <div class="card-header">
                            <h3 class="card-title" style="color: #4e73df">
                                <i class="fas fa-chart-area"></i> Xu hướng đặt hàng
                            </h3>

                            {{-- Form Lọc Thời Gian --}}
                            <form method="GET" action="{{ route('admin.statistical') }}#sec-orders"
                                id="orderTrendForm">
                                {{-- Giữ lại các tham số filter khác để không bị mất khi reload --}}
                                @if (request('filter'))
                                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                                @endif
                                @if (request('rating_star'))
                                    <input type="hidden" name="rating_star" value="{{ request('rating_star') }}">
                                @endif

                                <select name="order_date_filter"
                                    onchange="document.getElementById('orderTrendForm').submit()"
                                    style="padding: 4px 8px; border-radius: 15px; border: 1px solid #e3e6f0; font-size: 12px; outline: none; color: #555;">
                                    <option value="day" {{ $orderDateFilter == 'day' ? 'selected' : '' }}>30 ngày
                                        qua</option>
                                    <option value="month" {{ $orderDateFilter == 'month' ? 'selected' : '' }}>Theo
                                        tháng (Năm nay)</option>
                                    <option value="hour" {{ $orderDateFilter == 'hour' ? 'selected' : '' }}>Khung
                                        giờ vàng (0-23h)</option>
                                </select>
                            </form>
                        </div>

                        {{-- VÙNG CHỨA BIỂU ĐỒ --}}
                        <div style="padding: 20px; height: 300px;">
                            <canvas id="orderTrendChart"></canvas>
                        </div>

                        <div
                            style="padding: 10px; background: #f8f9fc; text-align: center; border-top: 1px solid #e3e6f0;">
                            <small style="color: #888;">Biểu đồ thể hiện số lượng đơn hàng được tạo theo thời
                                gian.</small>
                        </div>
                    </div>

                    {{-- 2. TOP 20 ĐƠN HÀNG GIÁ TRỊ NHẤT --}}
                    <div class="custom-card border-top-success" style="border-top: 4px solid #1cc88a;">
                        <div class="card-header">
                            <h3 class="card-title" style="color: #1cc88a">
                                <i class="fas fa-trophy"></i> Top 10 Đơn Giá Trị Cao Nhất
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
                                                <span style="font-size:10px; color:#999">mã đơn:
                                                    #{{ $order->id }}</span>
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

            // --- CẤU HÌNH MÀU SẮC CHUNG CHO BIỂU ĐỒ TRÒN ---
            const pieColors = [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
                '#858796', '#5a5c69', '#f8f9fc', '#2e59d9', '#17a673'
            ];

            // 1. BIỂU ĐỒ DOANH THU THEO BRAND (Doughnut)
            const ctxBrand = document.getElementById("brandRevenueChart");
            if (ctxBrand) {
                new Chart(ctxBrand.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($brandLabels),
                        datasets: [{
                            data: @json($brandData),
                            backgroundColor: pieColors,
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        // Format tiền Việt trong tooltip
                                        let value = context.raw;
                                        return context.label + ': ' + new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(value);
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

            // 2. BIỂU ĐỒ DOANH THU THEO CATEGORY (Pie)
            const ctxCat = document.getElementById("categoryRevenueChart");
            if (ctxCat) {
                new Chart(ctxCat.getContext('2d'), {
                    type: 'pie', // Dùng Pie chart cho khác biệt xíu
                    data: {
                        labels: @json($catLabels),
                        datasets: [{
                            data: @json($catData),
                            backgroundColor: pieColors,
                            hoverBorderColor: "rgba(234, 236, 244, 1)",
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let value = context.raw;
                                        return context.label + ': ' + new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(value);
                                    }
                                }
                            }
                        },
                    },
                });
            }
            //Thống kê đánh giá
            // --- 1. BIỂU ĐỒ TRÒN (PIE CHART) ---
            var ctxPie = document.getElementById("ratingPieChart");
            var ratingPieChart = new Chart(ctxPie, {
                type: 'doughnut',
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
            const ctxOrderTrend = document.getElementById('orderTrendChart');

            if (ctxOrderTrend) {
                // Tạo gradient màu tím nhạt cho vùng dưới biểu đồ
                let gradientTrend = ctxOrderTrend.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradientTrend.addColorStop(0, 'rgba(78, 115, 223, 0.2)'); // Màu đậm ở trên
                gradientTrend.addColorStop(1, 'rgba(78, 115, 223, 0.0)'); // Màu nhạt ở dưới

                new Chart(ctxOrderTrend.getContext('2d'), {
                    type: 'bar', // Biểu đồ đường
                    data: {
                        labels: @json($orderChartLabels), // Ví dụ: ["1h", "2h"...] hoặc ["01/12", "02/12"...]
                        datasets: [{
                            label: 'Số đơn hàng',
                            data: @json($orderChartData), // Dữ liệu số lượng: [5, 12, 8...]
                            borderColor: '#4e73df', // Màu đường kẻ (Xanh dương)
                            backgroundColor: gradientTrend, // Màu nền gradient
                            borderWidth: 2,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4e73df',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true, // Tô màu vùng dưới đường
                            tension: 0.3 // Độ cong mềm mại (0 = đường thẳng)
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }, // Ẩn chú thích vì chỉ có 1 đường
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.raw + ' đơn hàng';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxTicksLimit: 12
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    borderDash: [2],
                                    color: "#f0f0f0"
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endsection
