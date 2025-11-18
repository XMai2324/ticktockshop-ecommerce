<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->paginate(10); // mỗi trang 10 đơn hàng
    

        return view('client.ordershistory', compact('orders'));
    }

    // public function show($id)
    // {
    //     $order = Order::with('items.product')->findOrFail($id);
    
    //     return view('client.ordershow', compact('order'));
    // }
   
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
    
        // Nếu là admin
        if (request()->is('admin/*')) {
            return view('admin.orders_show', compact('order'));
        }
    
        // Nếu là client
        return view('client.ordershow', compact('order'));
    }
    
   
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        return view('admin.orders_index', compact('orders'));
    }

    // Chi tiết đơn hàng
    // public function show($id)
    // {
    //     $order = Order::with('items.product')->findOrFail($id);
    //     return view('admin.orders_show', compact('order'));
    // }

    // Sửa đơn hàng
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders_edit', compact('order'));
    }

    // Cập nhật đơn hàng
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Cập nhật đơn hàng thành công!');
    }

    // Xóa đơn hàng
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Xóa đơn hàng thành công!');
    }
    
}
