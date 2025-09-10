<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;


use Illuminate\Http\Request;

class WarrantyController extends Controller
{
        public function showClient()
    {
        return view('client.warranty');
    }

    public function showAdmin()
    {
        return view('admin.warranty_index');
    }

    public function lookup(Request $request)
    {
        $phone = $request->input('phone_number');

        // Lấy các đơn hàng theo số điện thoại
        $orders = Order::where('phone', $phone)->get();

        if ($orders->isEmpty()) {
            $warrantyResults = collect(); // trả về collection rỗng
        } else {
            // Lấy toàn bộ order_id
            $orderIds = $orders->pluck('id');
            $ordersById = $orders->keyBy('id');

            // Lấy các mục trong đơn hàng (order_items)
            $orderItems = OrderItem::whereIn('order_id', $orderIds)->get();

            // Lấy danh sách product_id và ánh xạ ID -> product name
            $productIds = $orderItems->pluck('product_id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            // Lấy warranty gắn với order_item
            $warranties = Warranty::whereIn('order_item_id', $orderItems->pluck('id'))->get()->keyBy('order_item_id');

            // Tổ hợp kết quả
            $warrantyResults = $orderItems->map(function ($item) use ($products, $warranties, $ordersById) {
            $order = $ordersById[$item->order_id] ?? null;

            return [
                'order_code'     => $order->order_code ?? 'Không xác định',
                'product_name'   => $products[$item->product_id]->name ?? 'Không xác định',
                //'start_date'     => $warranties[$item->id]->start_date ?? null,
                'start_date'     => $order?->created_at ?? null,
                'end_date'       => $warranties[$item->id]->end_date ?? null,
                'status'         => $warranties[$item->id]->status ?? 'Chưa xác định',
                    ];
                });
                    $customer_name = $orders->first()->customer_name;

            }

            return view(auth()->check() && auth()->user()->role === 'admin'
        ? 'admin.warranty_index'
        : 'client.warranty', [
            'customer_name' => $customer_name ?? null,
            'warrantyResults' => $warrantyResults
        ]);
    }
}

