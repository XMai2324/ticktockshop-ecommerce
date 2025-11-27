@extends('admin.dashboard')

@section('content')

<style>
    :root {
        --primary-color: #4e73df; /* Màu xanh chủ đạo */
        --success-color: #1cc88a; /* Màu xanh lá (Doanh thu) */
        --text-color: #5a5c69;
        --bg-card: #ffffff;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --radius: 10px;
    }

    .dashboard-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        background-color: #f8f9fc; /* Màu nền xám nhạt */
    }

    .page-title {
        color: #333;
        margin-bottom: 25px;
        font-size: 24px;
        font-weight: 600;
        border-left: 5px solid var(--primary-color);
        padding-left: 10px;
    }

    /*--- CSS GRID CHO CÁC THẺ CARD ---*/
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); /* Tự động chia cột responsive */
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--bg-card);
        padding: 20px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border-left: 5px solid var(--primary-color); /* Đường viền màu bên trái */
        transition: transform 0.2s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card:hover {
        transform: translateY(-5px); /* Hiệu ứng nổi lên khi di chuột */
    }

    .stat-content h3 {
        margin: 0;
        font-size: 14px;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-content p {
        margin: 5px 0 0 0;
        font-size: 22px;
        font-weight: bold;
        color: #333;
    }

    .stat-icon {
        font-size: 30px;
        color: #dddfeb;
    }

    /* Màu riêng cho từng card */
    .card-green { border-left-color: var(--success-color); }
    .card-green .stat-content p { color: var(--success-color); }

    /*--- CSS CHO KHUNG BIỂU ĐỒ ---*/
    .chart-section {
        background: var(--bg-card);
        padding: 20px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 30px;
    }

    .chart-header {
        border-bottom: 1px solid #e3e6f0;
        padding-bottom: 15px;
        margin-bottom: 15px;
        font-weight: bold;
        color: var(--primary-color);
    }

    .chart-canvas-container {
        position: relative;
        height: 400px;
        width: 100%;
    }
</style>

{{-- 2. PHẦN NỘI DUNG HTML --}}
<div class="dashboard-container">
    <h1 class="page-title">Thống kê tổng quan</h1>

    {{-- Hàng các thẻ Card --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <h3>Sản phẩm</h3>
                <p>{{ number_format($total_products) }}</p>
            </div>
            <div class="stat-icon">📦</div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <h3>Khách hàng</h3>
                <p>{{ number_format($total_users) }}</p>
            </div>
            <div class="stat-icon">👥</div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <h3>Đơn hàng</h3>
                <p>{{ number_format($total_orders) }}</p>
            </div>
            <div class="stat-icon">🛒</div>
        </div>

        <div class="stat-card card-green">
            <div class="stat-content">
                <h3>Doanh thu</h3>
                <p>{{ number_format($total_revenue) }} đ</p>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    {{-- Phần Biểu đồ --}}
    <div class="chart-section">
        <div class="chart-header">Biểu đồ doanh thu 6 tháng gần nhất</div>
        <div class="chart-canvas-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

{{-- 3. SCRIPT VẼ BIỂU ĐỒ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');

    // Gradient màu cho đẹp (Hiệu ứng mờ dần bên dưới đường biểu đồ)
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(78, 115, 223, 0.5)'); // Màu đậm ở trên
    gradient.addColorStop(1, 'rgba(78, 115, 223, 0.0)'); // Màu nhạt dần xuống dưới

    new Chart(ctx, {
        type: 'line', // Loại biểu đồ đường
        data: {
            labels: @json($months).map(m => "Tháng " + m), // Thêm chữ "Tháng" vào trục hoành
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($revenues),
                backgroundColor: gradient,
                borderColor: '#4e73df',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4e73df',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true, // Tô màu bên dưới đường
                tension: 0.4 // Làm mượt đường cong (0 là đường thẳng gấp khúc)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Để biểu đồ co giãn theo khung CSS
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            // Format tiền tệ VNĐ trong tooltip
                            label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            // Format trục tung thành dạng tiền tệ rút gọn (VD: 1tr)
                            return value.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
