<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN'); // Tạo dữ liệu giả tiếng Việt

        // 1. Kiểm tra dữ liệu đầu vào
        $products = Product::all();
        $users = User::all();

        if ($products->count() == 0 || $users->count() == 0) {
            $this->command->error('Cần có User và Product trong database trước!');
            return;
        }

        // Danh sách trạng thái đơn hàng (dựa trên Enum trong ảnh)
        $statuses = ['pending', 'confirmed', 'delivered', 'cancelled'];


        // 2. Tạo 50 đơn hàng mẫu
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();

            // Random ngày tạo trong năm nay (để test biểu đồ thống kê)
            $createdAt = Carbon::now()->subDays(rand(0, 300))->setTime(rand(8, 22), rand(0, 59));

            // Random trạng thái (ưu tiên 'delivered' để có doanh thu test)
            $status = $statuses[rand(0, 3)];
            if(rand(1, 10) <= 7) $status = 'delivered';

            // --- TẠO ĐƠN HÀNG (Khởi tạo giá trị 0 trước) ---
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $user->name ?? $faker->name, // Lấy tên user hoặc tên giả
                'phone' => $user->phone ?? $faker->phoneNumber,
                'address' => $user->address ?? $faker->address,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,

                // Các trường giá tiền tạm thời để 0, sẽ update sau khi tính toán items
                'total_price' => 0,
                'discount_amount' => 0,
                'final_price' => 0,
                'promotion_code' => null,
                'promotion_id' => null,
            ]);

            // --- TẠO CHI TIẾT ĐƠN HÀNG (ORDER ITEMS) ---
            $subTotal = 0;
            $randomProducts = $products->random(rand(1, 4)); // Mua 1-4 món ngẫu nhiên

            foreach ($randomProducts as $product) {
                $qty = rand(1, 3);
                $price = $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $price,
                    // Nếu bảng order_items của bạn có cột total
                    // 'total' => $qty * $price
                ]);

                $subTotal += ($qty * $price);
            }

            // --- TÍNH TOÁN KHUYẾN MÃI & GIÁ CUỐI ---
            $discountAmount = 0;

            $finalPrice = $subTotal - $discountAmount;
            if ($finalPrice < 0) $finalPrice = 0;

            $promoCode = null;
            // --- CẬP NHẬT LẠI ĐƠN HÀNG ---
            $order->update([
                'total_price' => $subTotal,        // Tổng tiền hàng
                'promotion_code' => $promoCode,    // Mã giảm giá
                'discount_amount' => $discountAmount, // Số tiền giảm
                'final_price' => $finalPrice       // Số tiền thực trả
            ]);
        }

        $this->command->info('Đã tạo xong 50 đơn hàng với dữ liệu đầy đủ!');
    }
}
