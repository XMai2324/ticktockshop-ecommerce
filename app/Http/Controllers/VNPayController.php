<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $amount = (int) $request->input('amount', 10000); // test tạm 10.000đ

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
            $grandTotal = 0;
            foreach ($cartItems as $item) {
                $grandTotal += ($item['qty'] ?? 0) * ($item['price'] ?? 0);
            }
    
            $order = \App\Models\Order::create([
                'user_id'       => auth()->id() ?? null,
                'customer_name' => $checkout['fullname'] ?? 'Khách hàng VNPay',
                'phone'         => $checkout['phone'] ?? '',
                'address'       => $checkout['address'] ?? '',
               
                'total_price'   => $request->vnp_Amount / 100, 
                'status'        => 'pending',
            ]);
            
    
            foreach ($cartItems as $item) {
                \App\Models\OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'] ?? $item['qty'], // GIỎ HÀNG DÙNG KEY NÀO?
                    'price'      => $item['price'], // đơn giá
                ]);
            }
            
    
            // Lưu thanh toán
            \App\Models\Payment::create([
                'order_id'        => $order->id,
                'method'          => 'bank',
                'status'          => 'pending',
                'transaction_code'=> $request->vnp_TxnRef,
                'payment_date'    => now(),
            ]);
    
            // Xóa session
            session()->forget(['cart','checkout_info']);
        } else if ($secureHash === $vnp_SecureHash) {
            $message = 'Thanh toán VNPay thất bại!';
        } else {
            $message = 'Dữ liệu không hợp lệ (chữ ký không khớp)!';
        }
    
        return view('client.vnpay_return', [
            'success' => $success,
            'message' => $message,
            'vnp_TxnRef' => $request->vnp_TxnRef,
            'vnp_Amount' => $request->vnp_Amount / 100,
            'vnp_ResponseCode' => $request->vnp_ResponseCode
        ]);
    }
    

}
