<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $promos = [
            // 1) Khách mới
            [
                'code'            => 'WATCHNEW15',
                'name'            => 'Khách mới: Giảm 15% (tối đa 150k)',
                'type'            => 'percent',
                'value'           => 15,
                'max_discount'    => 150000,
                'min_order_value' => 1500000,
                'usage_limit'     => 5000,
                'per_user_limit'  => 1,
                'end_plus_months' => 2,
            ],

            // 2) Đơn phổ biến tầm 1–2 triệu
            [
                'code'            => 'CASIO50',
                'name'            => 'Giảm thẳng 50k (đơn từ 1.000.000đ)',
                'type'            => 'fixed',
                'value'           => 50000,
                'max_discount'    => null,
                'min_order_value' => 1000000,
                'usage_limit'     => 5000,
                'per_user_limit'  => 3,
                'end_plus_months' => 2,
            ],

            // 3) Cặp đôi
            [
                'code'            => 'COUPLE100',
                'name'            => 'Giảm thẳng 100k (đơn cặp đôi từ 1.800.000đ)',
                'type'            => 'fixed',
                'value'           => 100000,
                'max_discount'    => null,
                'min_order_value' => 1800000,
                'usage_limit'     => 3000,
                'per_user_limit'  => 2,
                'end_plus_months' => 2,
            ],

            // 4) Tầm trung ~1–1.5 triệu
            [
                'code'            => 'MID12',
                'name'            => 'Giảm 12% (tối đa 120k, đơn từ 1.000.000đ)',
                'type'            => 'percent',
                'value'           => 12,
                'max_discount'    => 120000,
                'min_order_value' => 1000000,
                'usage_limit'     => 4000,
                'per_user_limit'  => 3,
                'end_plus_months' => 2,
            ],

            // 5) Khuyến mãi cuối tuần / flash sale nhẹ
            [
                'code'            => 'WEEKEND80',
                'name'            => 'Giảm thẳng 80k (đơn từ 1.200.000đ)',
                'type'            => 'fixed',
                'value'           => 80000,
                'max_discount'    => null,
                'min_order_value' => 1200000,
                'usage_limit'     => 2000,
                'per_user_limit'  => 2,
                'end_plus_months' => 1,
            ],

            // 6) Dòng cao cấp
            [
                'code'            => 'LUX7',
                'name'            => 'Giảm 7% (tối đa 400k, đơn từ 5.000.000đ)',
                'type'            => 'percent',
                'value'           => 7,
                'max_discount'    => 400000,
                'min_order_value' => 5000000,
                'usage_limit'     => 2000,
                'per_user_limit'  => 2,
                'end_plus_months' => 3,
            ],

            // 7) Đơn rất lớn
            [
                'code'            => 'VIP200',
                'name'            => 'Giảm thẳng 200k (đơn từ 8.000.000đ)',
                'type'            => 'fixed',
                'value'           => 200000,
                'max_discount'    => null,
                'min_order_value' => 8000000,
                'usage_limit'     => 1000,
                'per_user_limit'  => 2,
                'end_plus_months' => 3,
            ],

            // 8) Dịp kỷ niệm / sự kiện
            [
                'code'            => 'ANNIV10',
                'name'            => 'Giảm 10% (tối đa 200k, đơn từ 2.000.000đ)',
                'type'            => 'percent',
                'value'           => 10,
                'max_discount'    => 200000,
                'min_order_value' => 2000000,
                'usage_limit'     => 3000,
                'per_user_limit'  => 2,
                'end_plus_months' => 2,
            ],

            // 9) Mức vào cửa thấp hơn một chút
            [
                'code'            => 'DAILY30',
                'name'            => 'Giảm thẳng 30k (đơn từ 800.000đ)',
                'type'            => 'fixed',
                'value'           => 30000,
                'max_discount'    => null,
                'min_order_value' => 800000,
                'usage_limit'     => 6000,
                'per_user_limit'  => 5,
                'end_plus_months' => 1,
            ],

            // 10) Flash đơn tầm 2.2 triệu
            [
                'code'            => 'FLASH100',
                'name'            => 'Giảm thẳng 100k (đơn từ 2.200.000đ)',
                'type'            => 'fixed',
                'value'           => 100000,
                'max_discount'    => null,
                'min_order_value' => 2200000,
                'usage_limit'     => 1500,
                'per_user_limit'  => 1,
                'end_plus_months' => 1,
            ],

            // 11) Đơn từ 1 tỉ: giảm 20% toàn đơn (không giới hạn)
            [
                'code'            => 'LEGEND20',
                'name'            => 'Đơn từ 1.000.000.000đ: Giảm 20% hóa đơn',
                'type'            => 'percent',
                'value'           => 20,
                'max_discount'    => null,           // không giới hạn
                'min_order_value' => 1_000_000_000,  // 1 tỉ
                'usage_limit'     => 100,
                'per_user_limit'  => 1,
                'end_plus_months' => 6,
            ],
            // 12) Đơn từ 20 triệu: giảm thẳng 2 triệu
            [
                'code'            => 'ELITE2M',
                'name'            => 'Giảm thẳng 2 triệu',
                'type'            => 'fixed',
                'value'           => 2_000_000,
                'max_discount'    => null,
                'min_order_value' => 20_000_000,
                'usage_limit'     => 300,
                'per_user_limit'  => 1,
                'end_plus_months' => 3,
            ],

            // 13) Đơn từ 30 triệu: 5% tối đa 5 triệu
            [
                'code'            => 'LUXE5',
                'name'            => 'Giảm 5% (tối đa 5 triệu)',
                'type'            => 'percent',
                'value'           => 5,
                'max_discount'    => 5_000_000,
                'min_order_value' => 30_000_000,
                'usage_limit'     => null,
                'per_user_limit'  => 2,
                'end_plus_months' => 4,
            ],

            // 14) Đơn từ 60 triệu: 8% tối đa 12 triệu
            [
                'code'            => 'PRESTIGE8',
                'name'            => 'Giảm 8% (tối đa 12 triệu)',
                'type'            => 'percent',
                'value'           => 8,
                'max_discount'    => 12_000_000,
                'min_order_value' => 60_000_000,
                'usage_limit'     => 500,
                'per_user_limit'  => 1,
                'end_plus_months' => 4,
            ],

            // 15) Đơn từ 100 triệu: 10% tối đa 20 triệu
            [
                'code'            => 'ROYAL10',
                'name'            => 'Giảm 10% (tối đa 20 triệu)',
                'type'            => 'percent',
                'value'           => 10,
                'max_discount'    => 20_000_000,
                'min_order_value' => 100_000_000,
                'usage_limit'     => 200,
                'per_user_limit'  => 1,
                'end_plus_months' => 6,
            ],

        ];
        foreach ($promos as $p) {
            Promotion::updateOrCreate(
                ['code' => $p['code']],
                [
                    'name'            => $p['name'],
                    'type'            => $p['type'],                 // 'percent' | 'fixed'
                    'value'           => $p['value'],                // % hoặc số tiền
                    'max_discount'    => $p['max_discount'] ?? null, // cho % thì set max
                    'min_order_value' => $p['min_order_value'] ?? 0,
                    'usage_limit'     => $p['usage_limit'] ?? null,
                    'per_user_limit'  => $p['per_user_limit'] ?? null,
                    'used_count'      => 0,
                    'is_active'       => true,
                    'start_at'        => $now->copy()->subDay(),
                    'end_at'          => $now->copy()->addMonths($p['end_plus_months'] ?? 2),
                ]
            );
        }
    }
}
