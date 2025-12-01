<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Rating;
use App\Models\OrderItem;
use Carbon\Carbon;

class RatingSeeder extends Seeder
{
    public function run()
    {
        // 1. Lấy tất cả đơn hàng đã giao thành công (Chỉ đơn thành công mới được đánh giá)
        // Đảm bảo load kèm orderItems để biết đơn đó mua gì
        $orders = Order::whereIn('status', ['delivered'])
                       ->with(['orderItems', 'user'])
                       ->get();

        if ($orders->isEmpty()) {
            $this->command->error("Không tìm thấy đơn hàng đã giao (delivered/completed) để tạo đánh giá.");
            return;
        }

        $count = 0;

        foreach ($orders as $order) {

            // Bỏ qua nếu đơn hàng không có người dùng (ví dụ khách vãng lai bị xóa user)
            if (!$order->user) continue;

            // Duyệt qua từng sản phẩm trong đơn hàng
            foreach ($order->orderItems as $item) {
                if (!$item->product_id) continue;
                // Random: Không phải ai mua xong cũng đánh giá (tỉ lệ 70% sẽ đánh giá)
                if (rand(1, 100) > 30) {

                    // Kiểm tra xem đã đánh giá chưa để tránh trùng lặp
                    $exists = Rating::where('order_id', $order->id)
                                    ->where('product_id', $item->product_id)
                                    ->exists();

                    if ($exists) continue;

                    // --- LOGIC TẠO NỘI DUNG ĐÁNH GIÁ ---
                    $rating = rand(3, 5); // Đa số đơn thành công thường đánh giá tốt (3-5 sao)

                    // Xui xui gặp khách khó tính (5% cơ hội bị 1-2 sao)
                    if (rand(1, 100) <= 5) {
                        $rating = rand(1, 2);
                    }

                    $comment = $this->getCommentByRating($rating);
                    $response = $this->getResponseByRating($rating);

                    // Ngày đánh giá: Phải sau ngày mua hàng ngẫu nhiên 1-5 ngày
                    $reviewDate = Carbon::parse($order->updated_at)->addDays(rand(1, 5));
                    if ($reviewDate->isFuture()) $reviewDate = Carbon::now();

                    Rating::create([
                        'user_id'    => $order->user_id,      // Người mua
                        'product_id' => $item->product_id,    // Sản phẩm đã mua
                        'order_id'   => $order->id,           // Mã đơn hàng (QUAN TRỌNG)
                        'rating'     => $rating,
                        'comment'    => $comment,
                        'response'   => rand(0, 1) ? $response : null, // 50% cơ hội được shop trả lời
                        'created_at' => $reviewDate,
                        'updated_at' => $reviewDate,
                    ]);

                    $count++;
                }
            }
        }

        $this->command->info("Đã tạo thành công {$count} đánh giá từ các đơn hàng thực tế!");
    }

    // Hàm phụ trợ: Lấy comment mẫu
    private function getCommentByRating($rating)
    {
        $good = [
            "Sản phẩm tuyệt vời, đúng như mô tả!",
            "Giao hàng siêu nhanh, đóng gói cẩn thận.",
            "Rất ưng ý, sẽ ủng hộ shop dài dài.",
            "Chất lượng tốt so với tầm giá.",
            "Đeo lên tay rất đẹp, sang trọng.",
            "Tuyệt vời!",
            "Ok, shop tư vấn nhiệt tình."
        ];

        $medium = [
            "Sản phẩm tạm ổn.",
            "Giao hàng hơi chậm nhưng hàng ok.",
            "Màu sắc hơi khác ảnh một chút.",
            "Dùng được, không quá xuất sắc.",
            "Đóng gói cần kỹ hơn nhé shop."
        ];

        $bad = [
            "Hàng không giống hình, thất vọng.",
            "Chất lượng kém, mới dùng đã hỏng.",
            "Giao sai mẫu, nhắn tin shop không trả lời.",
            "Quá tệ, không bao giờ quay lại.",
            "Phí tiền!"
        ];

        if ($rating >= 4) return $good[array_rand($good)];
        if ($rating == 3) return $medium[array_rand($medium)];
        return $bad[array_rand($bad)];
    }

    // Hàm phụ trợ: Lấy phản hồi của Shop
    private function getResponseByRating($rating)
    {
        if ($rating >= 4) {
            return "Cảm ơn bạn đã tin tưởng ủng hộ Shop ạ! Mong được phục vụ bạn lần sau.";
        }
        if ($rating == 3) {
            return "Cảm ơn bạn đã góp ý, Shop sẽ cố gắng cải thiện dịch vụ hơn ạ.";
        }
        return "Shop rất xin lỗi về trải nghiệm không tốt này. Vui lòng inbox để Shop hỗ trợ đổi trả/hoàn tiền ngay cho bạn nhé!";
    }
}
