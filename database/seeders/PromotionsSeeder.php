<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Carbon\Carbon;



class PromotionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promotion::create([
            'name'            => 'Khuyến mãi tháng 9',
            'code'            => 'SALESEP',
            'type'            => 'percent', // giảm %
            'value'           => 10,        // giảm 10%
            'max_discount'    => 100000,    // tối đa 100k
            'min_order_value' => 200000,    // đơn tối thiểu 200k
            'usage_limit'     => 100,
            'per_user_limit'  => 2,
            'used_count'      => 0,
            'is_active'       => true,
            'start_at'        => Carbon::now()->subDay(),   // bắt đầu hôm qua
            'end_at'          => Carbon::now()->addDays(7), // còn 7 ngày nữa
        ]);

        // Mã HẾT HẠN
        Promotion::create([
            'name'            => 'Khuyến mãi mùa hè',
            'code'            => 'SUMMER2024',
            'type'            => 'fixed', // trừ thẳng tiền
            'value'           => 50000,   // giảm 50k
            'max_discount'    => null,
            'min_order_value' => 100000,
            'usage_limit'     => 50,
            'per_user_limit'  => 1,
            'used_count'      => 0,
            'is_active'       => true,
            'start_at'        => Carbon::now()->subMonths(2), // bắt đầu 2 tháng trước
            'end_at'          => Carbon::now()->subMonth(),   // đã hết hạn 1 tháng
        ]);
    }
}

