<?php   

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;

class VNPayController extends Controller
{
    public function createPayment(Request $request)
    {
        session(['checkout_info' => $request->only([
            'fullname', 'phone', 'email', 'address', 'district', 'province'
        ])]);
        
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = config('vnpay.vnp_Returnurl');
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');

        // Lấy tổng tiền từ giỏ hàng (VD: đã tính ở controller khác)
        // Lưu ý: Nên tính lại từ session cart để bảo mật, tránh user sửa html input
        $amount = (int) $request->input('amount', 10000); 

        $vnp_TxnRef = time(); // Mã đơn hàng
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $vnp_TxnRef;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // Nhân 100 theo chuẩn VNPay
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);
        $query = "";
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect()->away($vnp_Url);
    }

    public function return(Request $request)
    {
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
       
        $inputData = $request->only(array_filter(array_keys($request->all()), fn($k) => str_starts_with($k, 'vnp_')));
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
        ksort($inputData);
       
        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
       
        $success = false;
        $message = 'Thanh toán thất bại hoặc bị hủy!';

        if ($secureHash === $vnp_SecureHash && $request->vnp_ResponseCode === '00') {
            $success = true;
            $message = 'Thanh toán VNPay thành công!';
       
            $checkout = session('checkout_info', []);
            $cartItems = session('cart', []);
            
            // Tính lại tổng tiền để lưu vào DB (nếu cần)
            // $grandTotal = 0;
            // foreach ($cartItems as $item) {
            //     // Sửa lỗi: kiểm tra key quantity hoặc qty
            //     $qty = $item['quantity'] ?? $item['qty'] ?? 0;
            //     $price = $item['price'] ?? 0;
            //     $grandTotal += $qty * $price;
            // }
       
            $order = Order::create([
                'user_id'       => auth()->id(),
                'customer_name' => $checkout['fullname'] ?? 'Khách hàng VNPay',
                'phone'         => $checkout['phone'] ?? '',
                'address'       => $checkout['address'] ?? '',
                'total_price'   => $request->vnp_Amount / 100, 
                'status'        => 'pending', // Có thể đổi thành 'processing' hoặc 'paid' vì đã thanh toán xong
            ]);
            
            foreach ($cartItems as $item) {
                // --- SỬA LỖI Ở ĐÂY ---
                // Lấy số lượng an toàn, ưu tiên 'quantity', nếu không có thì lấy 'qty', không có nữa thì mặc định 1
                $buyQty = $item['quantity'] ?? $item['qty'] ?? 1; 
                $price = $item['price'] ?? 0;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $buyQty, 
                    'price'      => $price,
                ]);

                // Trừ tồn kho
                $product = Product::find($item['id']);
                if ($product) {
                    // Sử dụng biến $buyQty đã lấy ở trên, không gọi $item['qty'] trực tiếp nữa
                    $product->decrement('quantity', $buyQty);

                    // Kiểm tra nếu âm kho thì set về 0 (tùy logic shop)
                    if ($product->quantity < 0) {
                        $product->quantity = 0;
                        $product->save();
                    }
                }
            }
     
            // Lưu lịch sử thanh toán
            Payment::create([
                'order_id'         => $order->id,
                'method'           => 'vnpay', // Nên ghi rõ là vnpay
                // SỬA LỖI TRUNCATED: Đổi 'completed' thành 'paid' để khớp với ENUM hoặc độ dài database
                'status'           => 'paid', 
                'transaction_code' => $request->vnp_TxnRef,
                'payment_date'     => now(),
            ]);
       
            // Xóa session
            session()->forget(['cart', 'checkout_info']);

        } else if ($secureHash === $vnp_SecureHash) {
            $message = 'Thanh toán VNPay thất bại!';
        } else {
            $message = 'Dữ liệu không hợp lệ (chữ ký không khớp)!';
        }
       
        return view('client.vnpay_return', [
            'success' => $success,
            'message' => $message,
            'vnp_TxnRef' => $request->vnp_TxnRef ?? null,
            'vnp_Amount' => ($request->vnp_Amount ?? 0) / 100,
            'vnp_ResponseCode' => $request->vnp_ResponseCode ?? null
        ]);
    }
}