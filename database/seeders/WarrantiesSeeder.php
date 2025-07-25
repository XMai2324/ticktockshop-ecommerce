<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warranty;
use Carbon\Carbon;

class WarrantiesSeeder extends Seeder
{
    public function run(): void
    {
        // Bảo hành còn hạn (order_item_id = 2)
        Warranty::create([
            'order_item_id' => 2,
            'warranty_code' => 'BH2025A123',
            'start_date' => Carbon::now()->subMonths(1)->toDateString(), // bắt đầu 1 tháng trước
            'end_date' => Carbon::now()->addMonths(11)->toDateString(), // còn 11 tháng nữa
            'status' => 'valid',
        ]);

        // Bảo hành đã hết hạn (order_item_id = 4)
        Warranty::create([
            'order_item_id' => 4,
            'warranty_code' => 'BH2023B456',
            'start_date' => Carbon::now()->subYears(2)->toDateString(), // 2 năm trước
            'end_date' => Carbon::now()->subMonths(6)->toDateString(), // hết hạn 6 tháng trước
            'status' => 'expired',
        ]);
    }
}
