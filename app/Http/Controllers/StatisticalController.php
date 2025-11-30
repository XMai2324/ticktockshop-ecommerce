<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticalController extends Controller
{
    public function index(Request $request)
    {
        // 1. DỮ LIỆU TỔNG QUAN
        $total_products = Product::count();
        $total_orders = Order::count();
        $total_revenue = Order::where('status', 'confirmed')->sum('total_price');
        $total_ratings = Rating::count();
        $avg_rating = round(Rating::avg('rating'), 1);

        // 2. THỐNG KÊ DOANH THU
        $filter = request('filter', 'month');
        $query = Order::where('status', 'confirmed');

        // Xử lý theo từng loại filter
        if ($filter == 'day') {
            // Theo ngày (Lấy 30 ngày gần nhất)
            $dataRaw = $query->select(
                    DB::raw('DATE_FORMAT(created_at, "%d/%m") as label'),
                    DB::raw('SUM(total_price) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('label')
                ->orderBy('created_at')
                ->get();

        } elseif ($filter == 'year') {
            // Theo năm (Lấy 5 năm gần nhất)
            $dataRaw = $query->select(
                    DB::raw('YEAR(created_at) as label'),
                    DB::raw('SUM(total_price) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subYears(5))
                ->groupBy('label')
                ->orderBy('label')
                ->get();

        } else {
            $dataRaw = $query->select(
                    DB::raw('MONTH(created_at) as label'),
                    DB::raw('SUM(total_price) as total')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('label')
                ->orderBy('label')
                ->get();

            $dataRaw->transform(function($item) {
                $item->label = "Tháng " . $item->label;
                return $item;
            });
        }
        $chart_labels = $dataRaw->pluck('label')->toArray();
        $chart_data   = $dataRaw->pluck('total')->toArray();

        // --- PHẦN 4: THỐNG KÊ SẢN PHẨM & KHO HÀNG ---

        // A. Sản phẩm Bán chạy nhất
        $top_selling_products = Product::bestSellerByRevenue(10)->get();
    // 2. SẮP HẾT HÀNG
        $low_stock_products = \App\Models\Product::with(['brand', 'category']) // <--- THÊM
            ->where('quantity', '<=', 10)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'asc')
            ->get();

        $out_of_stock_count = \App\Models\Product::where('quantity', 0)->count();
        // 3. HÀNG TỒN ĐỌNG
        $unsold_products = \App\Models\Product::with(['brand', 'category']) // <--- THÊM
            ->whereDoesntHave('orderItems.order', function($q) {
                $q->where('status', 'delivered');
            })
            ->select('id', 'name', 'image', 'price', 'created_at', 'quantity', 'brand_id', 'category_id') // <--- THÊM ID quan hệ
            ->orderBy('created_at', 'asc')
            ->get();

        // --- PHẦN 3: THỐNG KÊ ĐÁNH GIÁ  ---

        //Dữ liệu Biểu đồ Tròn (Số lượng từng loại sao: 1, 2, 3, 4, 5)
        $star_counts = Rating::select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $pie_data = [];
        for ($i = 1; $i <= 5; $i++) {
            $pie_data[] = $star_counts[$i] ?? 0;
        }

        $top_reviewed_products = Product::withCount('ratings')
            ->orderBy('ratings_count', 'desc')
            ->take(5)
            ->get();
        // Tách mảng tên và số lượng để vẽ chart
        $bar_labels = $top_reviewed_products->pluck('name')->toArray();
        $bar_data   = $top_reviewed_products->pluck('ratings_count')->toArray();

        // Dữ liệu Bảng bên phải (Có bộ lọc)
        $filter_star = $request->input('rating_star', 'all'); // Mặc định lấy tất cả sao
        $filter_sort = $request->input('rating_sort', 'desc'); // Mặc định giảm dần

        // Query sản phẩm kèm theo đếm số lượng rating (có điều kiện lọc)
        $table_products = Product::withCount(['ratings' => function ($query) use ($filter_star) {
                if ($filter_star != 'all') {
                    $query->where('rating', $filter_star);
                }
            }])
            // Chỉ lấy những sản phẩm có rating > 0 theo tiêu chí lọc
            ->having('ratings_count', '>', 0)
            ->orderBy('ratings_count', $filter_sort)
            ->take(15)
            ->get();

        // Danh sách sản phẩm chưa có đánh giá
        $unrated_products = Product::doesntHave('ratings')
        ->orderBy('created_at', 'desc') // Ưu tiên sản phẩm mới nhập
        ->take(20)
        ->get();

    // --- PHẦN 5: THỐNG KÊ ĐƠN HÀNG (MỚI) ---

    // A. Đơn hàng có tổng tiền lớn nhất
    $top_value_orders = \App\Models\Order::with('user')
        ->orderBy('total_price', 'desc')
        ->take(10) // Lấy 10 đơn
        ->get();

// B. Top 5 Đơn hàng nhiều sản phẩm nhất (Sỉ/Gom đơn)
    $most_products_orders = Order::with('user')
        ->withSum('orderItems', 'quantity') // Tính tổng số lượng item
        ->orderBy('order_items_sum_quantity', 'desc')
        ->take(5) // Lấy 5 đơn
        ->get();

    // C. Dữ liệu biểu đồ trạng thái đơn hàng
    // Kết quả: ['completed' => 50, 'pending' => 10, 'cancelled' => 5]
    $order_status_counts = Order::select('status',DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    // Chuẩn bị dữ liệu cho Chart.js
    // Định nghĩa các trạng thái muốn hiển thị và màu sắc tương ứng
    $status_labels_map = [
        'pending'   => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'shipping'  => 'Đang giao',
        'delivered' => 'Giao thành công',
        'cancelled' => 'Đã hủy'
    ];

    // Tạo mảng label và data theo thứ tự
    $status_chart_labels = [];
    $status_chart_data = [];

    foreach ($order_status_counts as $status => $count) {
        // Lấy tên tiếng Việt, nếu không có thì lấy tên gốc
        $status_chart_labels[] = $status_labels_map[$status] ?? ucfirst($status);
        $status_chart_data[] = $count;
    }


        return view('admin.statistical', compact(
            'total_products',
            'total_orders',
            'total_revenue',
            'total_ratings',
            'avg_rating',
            'chart_labels',
            'chart_data',
            'filter',
            'pie_data',
            'bar_labels',
            'bar_data',
            'table_products',
            'filter_star',
            'filter_sort',
            'unrated_products',
            'top_selling_products',
            'low_stock_products',
            'out_of_stock_count',
            'unsold_products',
            'top_value_orders',
            'most_products_orders',
            'status_chart_labels',
            'status_chart_data'
        ));
    }
}
