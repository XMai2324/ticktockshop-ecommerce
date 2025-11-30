@extends('admin.dashboard')

@section('content')

<div class="dashboard-wrapper">

    {{-- PHẦN HEADER ĐỨNG YÊN --}}
    <div class="stats-header-fixed">

    <a href="#sec-products" class="stat-card-link">
        <div class="stat-card border-blue"> <div class="stat-info">
                <h3>Tổng sản phẩm</h3>
                <p>{{ number_format($total_products) }}</p>
            </div>
            <div class="stat-icon">📦</div>
        </div>
    </a>

    <a href="#sec-revenue" class="stat-card-link">
        <div class="stat-card border-green"> <div class="stat-info">
                <h3>Doanh thu</h3>
                <p>{{ number_format($total_revenue) }} <small>đ</small></p>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </a>

    <a href="#sec-ratings" class="stat-card-link">
        <div class="stat-card border-yellow"> <div class="stat-info">
                <h3>Đánh giá TB</h3>
                <p>{{ $avg_rating }} / 5 ⭐
                <span style="font-size: 12px; color: #888; font-style: italic;">
                    ({{ $total_ratings }} lượt)
                </span></p>
            </div>
            <div class="stat-icon">💬</div>
        </div>
    </a>

    <a href="#sec-orders" class="stat-card-link">
        <div class="stat-card border-cyan"> <div class="stat-info">
                <h3>Tổng đơn hàng</h3>
                <p>{{ number_format($total_orders) }}</p>
            </div>
            <div class="stat-icon">🛒</div>
        </div>
    </a>

</div>

    {{-- PHẦN NỘI DUNG CUỘN --}}
    <div class="stats-content-scroll">

        <div id="sec-overview" class="detail-section">
            <h2 class="section-title">📦 Tổng quan đơn hàng</h2>
            <p>Nội dung thống kê đơn hàng...</p>
        </div>

        <div id="sec-revenue" class="detail-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0 border-0 p-0">💰 Biểu đồ Doanh thu</h2>

                <form method="GET" action="{{ route('admin.statistical') }}#sec-revenue" id="filterForm">
                    <select name="filter" class="form-control custom-select"
                            onchange="document.getElementById('filterForm').submit()">
                        <option value="day" {{ $filter == 'day' ? 'selected' : '' }}>Theo 30 ngày qua</option>
                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Theo tháng (Năm nay)</option>
                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Theo các năm</option>
                    </select>
                </form>
            </div>

            <div style="height: 400px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- <div id="sec-products" class="detail-section">
            <h2 class="section-title">👗 Top sản phẩm bán chạy</h2>
            <table class="table">
                <thead><tr><th>Tên</th><th>Giá</th><th>Lượt xem</th></tr></thead>
                <tbody>
                    @foreach($top_products as $p)
                    <tr><td>{{ $p->name }}</td><td>{{ number_format($p->price) }}</td><td>{{ $p->view_count }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div> --}}

        <div id="sec-ratings" class="detail-section">
            <h2 class="section-title">⭐ Phân tích Đánh giá & Phản hồi</h2>
            <div class="row">
                <div class="col-lg-7">
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
                <div class="col-lg-5">
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
                                    <form method="GET" action="{{ route('admin.statistical') }}#sec-ratings" id="ratingFilterForm">
                                        @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"> @endif

                                        <div class="row">
                                            <div class="col-6 pr-1">
                                                <small class="text-muted font-weight-bold d-block mb-1">Lọc sao:</small>
                                                <select name="rating_star" class="custom-select custom-select-sm shadow-none" onchange="this.form.submit()">
                                                    <option value="all" {{ $filter_star == 'all' ? 'selected' : '' }}>Tất cả</option>
                                                    <option value="5" {{ $filter_star == '5' ? 'selected' : '' }}>5 Sao (Tuyệt vời)</option>
                                                    <option value="4" {{ $filter_star == '4' ? 'selected' : '' }}>4 Sao (Tốt)</option>
                                                    <option value="3" {{ $filter_star == '3' ? 'selected' : '' }}>3 Sao (TB)</option>
                                                    <option value="2" {{ $filter_star == '2' ? 'selected' : '' }}>2 Sao (Tệ)</option>
                                                    <option value="1" {{ $filter_star == '1' ? 'selected' : '' }}>1 Sao (Rất tệ)</option>
                                                </select>
                                            </div>
                                            <div class="col-6 pl-1">
                                                <small class="text-muted font-weight-bold d-block mb-1">Thứ tự:</small>
                                                <select name="rating_sort" class="custom-select custom-select-sm shadow-none" onchange="this.form.submit()">
                                                    <option value="desc" {{ $filter_sort == 'desc' ? 'selected' : '' }}>Cao nhất ⬇</option>
                                                    <option value="asc" {{ $filter_sort == 'asc' ? 'selected' : '' }}>Thấp nhất ⬆</option>
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
                                                <span class="product-name-truncate font-weight-bold text-dark" title="{{ $p->name }}">
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
                                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486754.png" width="60" class="mb-2 opacity-50"><br>
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
        {{-- Thêm khoảng trắng dưới cùng để phần cuối có thể cuộn lên cao --}}
        <div style="height: 100px;"></div>
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
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
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
                backgroundColor: ['#e74a3b', '#e09d3b', '#f6c23e', '#36b9cc', '#1cc88a'], // Đỏ, Cam, Vàng, Xanh dương, Xanh lá
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
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
                x: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

@endsection
