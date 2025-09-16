<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        // Ví dụ: nếu Anh đang lưu giỏ ở session dưới dạng objects hoặc arrays
        $raw = session('cart', []); // hoặc lấy từ Cart::content() hay DB

        $cartItems = collect($raw)->map(function ($row) {
            $name  = is_array($row) ? ($row['name'] ?? '')     : ($row->name ?? '');
            $qty   = is_array($row) ? ($row['qty'] ?? ($row['quantity'] ?? 1))
                                    : ($row->qty ?? ($row->quantity ?? 1));
            $price = is_array($row) ? ($row['price'] ?? 0)     : ($row->price ?? 0);

            return [
                'name'  => $name,
                'qty'   => (int) $qty,
                'price' => (int) $price,
            ];
        })->values()->all();

        $subtotal = collect($cartItems)->sum(fn($i) => $i['qty'] * $i['price']);
        $shipping = 0;
        $discount = 0;
        $grandTotal = max(0, $subtotal + $shipping - $discount);

        $promotions = [
            ['code' => 'SALE20',   'label' => 'SALE20 - Giảm 20k',  'type' => 'fixed',   'value' => 20000],
            ['code' => 'NEW50',    'label' => 'NEW50 - Giảm 50k',   'type' => 'fixed',   'value' => 50000],
            ['code' => 'FREESHIP', 'label' => 'FREESHIP - Miễn phí vận chuyển', 'type' => 'freeship', 'value' => 0],
        ];

        return view('client.checkout', compact('cartItems','subtotal','shipping','discount','grandTotal','promotions'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'fullname'        => ['required','string','max:255'],
            'phone'           => ['required','string','max:20'],
            'email'           => ['required','email'],
            'province'        => ['required','string','max:255'],
            'district'        => ['required','string','max:255'],
            'address'         => ['required','string','max:500'],
            'payment_method'  => ['required','in:cash,bank'],
            'promo_code'      => ['nullable','string','max:100'],
        ]);

        return redirect()->route('checkout.show')->with('success', 'Đặt hàng thành công!');
    }
}
