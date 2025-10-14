<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class CheckoutController extends Controller
{
    // GET /checkout
    public function index(Request $request)
    {
<<<<<<< HEAD
        
        $raw = session('cart', []); // hoặc lấy từ Cart::content() hay DB
=======
        $cartItems = $this->normalizeCart(session('cart', []));
        $subtotal  = $this->cartSubtotal($cartItems);
        $shipping  = $this->shippingFee();
        $discount  = (int) session('coupon.discount_amount', 0);
        $grandTotal= max(0, $subtotal + $shipping - $discount);
>>>>>>> 3366b25ca99a902aa845f5804fc5ec5e7ab4a42d

        // render sẵn danh sách mã cho modal
        $availableCoupons = $this->queryAvailableCoupons($subtotal);

        return view('client.checkout', compact(
            'cartItems','subtotal','shipping','discount','grandTotal','availableCoupons'
        ));
    }

    // POST /checkout
    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'fullname'       => ['required','string','max:255'],
            'phone'          => ['required','string','max:20'],
            'email'          => ['required','email'],
            'province'       => ['required','string','max:255'],
            'district'       => ['required','string','max:255'],
            'address'        => ['required','string','max:500'],
            'payment_method' => ['required','in:cash,bank'],
        ]);

        // TODO: lưu đơn hàng tại đây

        return redirect()->route('checkout')->with('success', 'Đặt hàng thành công!');
    }

    // GET /coupons/available - ai cũng xem được
    public function availableCoupons(Request $request)
    {
        $subtotal = $this->cartSubtotal($this->normalizeCart(session('cart', [])));
        $coupons  = $this->queryAvailableCoupons($subtotal);
        return response()->json($coupons);
    }

    // POST /checkout/apply-coupon - yêu cầu đăng nhập (đã middleware ở routes)
    public function applyCoupon(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Vui lòng đăng nhập để sử dụng mã khuyến mãi.'], 401);
        }

        $code     = strtoupper(trim((string) $request->input('code')));
        $items    = $this->normalizeCart(session('cart', []));
        $subtotal = $this->cartSubtotal($items);
        $shipping = $this->shippingFee();

        $cp = Promotion::where('code', $code)
            ->where('is_active', true) // sửa: dùng is_active thay vì status
            ->first();

        if (!$cp) {
            return response()->json(['message' => 'Mã không tồn tại hoặc không còn hiệu lực'], 422);
        }
        if ($cp->start_at && now()->lt($cp->start_at)) {
            return response()->json(['message' => 'Mã chưa đến thời gian sử dụng'], 422);
        }
        if ($cp->end_at && now()->gt($cp->end_at)) {
            return response()->json(['message' => 'Mã đã hết hạn'], 422);
        }
        if ($cp->min_order_value && $subtotal < (int) round($cp->min_order_value)) {
            return response()->json(['message' => 'Đơn hàng chưa đạt giá trị tối thiểu'], 422);
        }

        // Tính giảm theo loại - migration của Anh chỉ có percent và fixed
        $discount = 0;
        switch ($cp->type) {
            case 'percent':
                $percent  = max(0, min(100, (float) $cp->value));
                $discount = (int) floor($subtotal * ($percent / 100));
                if (!is_null($cp->max_discount)) {
                    $discount = min($discount, (int) round($cp->max_discount));
                }
                $discount = min($discount, $subtotal);
                break;

            case 'fixed':
                $discount = (int) round((float) $cp->value);
                $discount = min($discount, $subtotal);
                break;

            default:
                return response()->json(['message' => 'Loại mã không hợp lệ'], 422);
        }

        // Lưu session
        session([
            'coupon' => [
                'code'            => $cp->code,
                'name'            => $cp->name,
                'type'            => $cp->type,
                'value'           => (float) $cp->value,
                'max_discount'    => (float) ($cp->max_discount ?? 0),
                'discount_amount' => (int) $discount,
            ]
        ]);

        $grand = max(0, $subtotal + $shipping - $discount);

        return response()->json([
            'coupon'          => ['code' => $cp->code, 'name' => $cp->name],
            'discount_amount' => (int) $discount,
            'grand_total'     => (int) $grand,
        ]);
    }

    // POST /checkout/remove-coupon - yêu cầu đăng nhập
    public function removeCoupon(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Vui lòng đăng nhập để thao tác với mã khuyến mãi.'], 401);
        }

        session()->forget('coupon');

        $items    = $this->normalizeCart(session('cart', []));
        $subtotal = $this->cartSubtotal($items);
        $shipping = $this->shippingFee();
        $grand    = max(0, $subtotal + $shipping);

        return response()->json(['grand_total' => (int) $grand]);
    }

    /* ================= Helpers ================= */

    private function normalizeCart(array $raw): array
    {
        return collect($raw)->map(function ($row) {
            $name  = is_array($row) ? ($row['name'] ?? '') : ($row->name ?? '');
            $qty   = is_array($row) ? ($row['qty'] ?? ($row['quantity'] ?? 1))
                                    : ($row->qty ?? ($row->quantity ?? 1));
            $price = is_array($row) ? ($row['price'] ?? 0) : ($row->price ?? 0);
            return [
                'name'  => (string) $name,
                'qty'   => (int) $qty,
                'price' => (int) $price,
            ];
        })->values()->all();
    }

    private function cartSubtotal(array $items): int
    {
        $sum = 0;
        foreach ($items as $i) {
            $sum += ((int) $i['qty']) * ((int) $i['price']);
        }
        return (int) $sum;
    }

    private function shippingFee(): int
    {
        // Có thể đổi theo logic của Anh
        return (int) session('shipping_fee', 0);
    }

    private function queryAvailableCoupons(int $subtotal)
    {
        return Promotion::query()
            ->where('is_active', true) // sửa: dùng is_active
            ->where(function ($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', now());
            })
            ->where(function ($q) use ($subtotal) {
                $q->whereNull('min_order_value')->orWhere('min_order_value', '<=', $subtotal);
            })
            ->orderBy('end_at')
            ->get(['id','code','name','type','value','max_discount','min_order_value','start_at','end_at']);
    }
}
