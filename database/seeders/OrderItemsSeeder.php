<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemsSeeder extends Seeder
{
    public function run(): void
    {
        // Đơn hàng 1
        OrderItem::create([
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
            'price' => 2500000,
        ]);

        OrderItem::create([
            'order_id' => 1,
            'product_id' => 2,
            'quantity' => 2,
            'price' => 1000000,
        ]);

        // Đơn hàng 2
        OrderItem::create([
            'order_id' => 2,
            'product_id' => 3,
            'quantity' => 1,
            'price' => 3000000,
        ]);

        OrderItem::create([
            'order_id' => 2,
            'product_id' => 4,
            'quantity' => 1,
            'price' => 2900000,
        ]);
    }
}
