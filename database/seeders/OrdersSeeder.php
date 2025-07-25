<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        Order::create([
            'user_id' => 2,
            'customer_name' => 'Customer 1',
            'phone' => '0901234512',
            'address' => 'Hà Nội',
            'total_price' => 3800000,
            'status' => 'confirmed',
        ]);

        Order::create([
            'user_id' => 3,
            'customer_name' => 'Mai',
            'phone' => '0911222333',
            'address' => 'TP. Hồ Chí Minh',
            'total_price' => 5900000,
            'status' => 'pending',
        ]);
    }
}
