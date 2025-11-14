<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;

class RatingSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $users = User::all()->pluck('id')->toArray();

        if ($products->isEmpty() || empty($users)) {
            dd("Không có products hoặc users để tạo dữ liệu.");
        }

        foreach ($products as $product) {

            // Random số lượng đánh giá / mỗi sản phẩm từ 3–10 đánh giá
            $reviewCount = rand(3, 10);

            for ($i = 0; $i < $reviewCount; $i++) {

                $rating = rand(1, 5);
                $userId = $users[array_rand($users)];

                // Comment mẫu theo số sao
                $goodComments = [
                    "Sản phẩm rất đẹp và chất lượng.",
                    "Đeo rất sang trọng, đúng mô tả.",
                    "Rất hài lòng với chất lượng.",
                    "Giao hàng nhanh, đóng gói kỹ.",
                    "Đáng tiền, sẽ quay lại shop.",
                ];

                $mediumComments = [
                    "Sản phẩm ổn, giá hợp lý.",
                    "Hơi khác ảnh một chút nhưng được.",
                    "Tạm được, chất lượng trung bình.",
                ];

                $badComments = [
                    "Sản phẩm không đúng mô tả.",
                    "Chất lượng kém, không hài lòng.",
                    "Hỏng sau vài ngày sử dụng.",
                ];

                if ($rating >= 4) {
                    $comment = $goodComments[array_rand($goodComments)];
                } elseif ($rating == 3) {
                    $comment = $mediumComments[array_rand($mediumComments)];
                } else {
                    $comment = $badComments[array_rand($badComments)];
                }

                // Phản hồi mẫu
                $responses = [
                    "Cảm ơn bạn đã phản hồi!",
                    "Shop xin ghi nhận và hỗ trợ ngay.",
                    "Cảm ơn bạn đã tin tưởng sản phẩm.",
                    "Shop rất tiếc về trải nghiệm của bạn, vui lòng liên hệ CSKH.",
                    null, // có thể chưa phản hồi
                ];

                Rating::create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'response' => $responses[array_rand($responses)],
                ]);
            }
        }

        echo " Tạo dữ liệu ratings hoàn tất!\n";
    }
}
