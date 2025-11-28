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
    public function index()
    {
        $dataRaw = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_price) as total')
        )
        ->where('status', 'completed') // QUAN TRỌNG: Chỉ tính đơn đã thành công (tùy status bên bạn)
        ->whereYear('created_at', date('Y')) // Lọc theo năm hiện tại (tránh cộng dồn tháng 1 năm ngoái với tháng 1 năm nay)
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

        // 2. Tách thành 2 mảng để gửi sang View (Chart.js cần dạng này)
        // pluck('month') sẽ lấy ra mảng [1, 2, 3...]
        // pluck('total') sẽ lấy ra mảng [100000, 200000, ...]
        $months = $dataRaw->pluck('month')->toArray();
        $revenues = $dataRaw->pluck('total')->toArray();

        // 3. Các chỉ số khác (giữ nguyên như cũ)
        $total_products = Product::count();
        $total_users = User::count();
        $total_orders = Order::count();
        $total_revenue = Order::where('status', 'completed')->sum('total_price');

        return view('admin.statistical', compact(
            'months',
            'revenues',
            'total_products',
            'total_users',
            'total_orders',
            'total_revenue'
        ));
    }
}
