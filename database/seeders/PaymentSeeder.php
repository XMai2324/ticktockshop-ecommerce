<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model; // Sửa dòng này nếu cần
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // 1. Lấy tất cả đơn hàng
        $orders = Order::all();

        if ($orders->isEmpty()) {
            $this->command->error('Cần chạy OrderSeeder trước!');
            return;
        }

        foreach ($orders as $order) {
            // Kiểm tra trùng lặp
            if (Payment::where('order_id', $order->id)->exists()) {
                continue;
            }

            // --- LOGIC CHO TIỀN MẶT (COD) ---

            $method = 'cash'; // Cố định phương thức
            $transactionCode = null; // Tiền mặt không có mã giao dịch

            $paymentStatus = 'pending';
            $paymentDate = null;

            // Logic: Chỉ khi khách ĐÃ NHẬN HÀNG (delivered/completed) thì mới tính là ĐÃ TRẢ TIỀN
            if (in_array($order->status, ['delivered', 'completed'])) {
                $paymentStatus = 'paid';
                // Ngày trả tiền = Ngày đơn hàng được cập nhật thành công (hoặc ngày tạo + vài ngày)
                $paymentDate = $order->updated_at;
            }
            elseif ($order->status == 'cancelled') {
                $paymentStatus = 'failed';
            }
            else {
                // Các trạng thái: pending, confirmed, shipping -> Tiền mặt thì chưa trả tiền
                $paymentStatus = 'pending';
            }

            // --- TẠO DỮ LIỆU ---
            Payment::create([
                'order_id' => $order->id,
                'method' => $method,
                'status' => $paymentStatus,
                'transaction_code' => $transactionCode,
                'payment_date' => $paymentDate,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]);
        }

        $this->command->info('Đã tạo xong dữ liệu thanh toán (Chỉ cash) cho ' . $orders->count() . ' đơn hàng.');
    }
}
