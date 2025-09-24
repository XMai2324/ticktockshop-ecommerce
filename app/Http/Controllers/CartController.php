<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = collect(session()->get('cart', []));
        $cartTotal = $cartItems->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('client.cart', compact('cartItems', 'cartTotal'));
    }

    public function addToCart(Request $request)
    {
        $id       = $request->input('id');
        $type     = $request->input('type');
        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);
        $key  = $type . '_' . $id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            if ($type === 'product') {
                $product = \App\Models\Product::with('category')->findOrFail($id);

                $categorySlug = \Illuminate\Support\Str::slug(optional($product->category)->name ?? '');
                $folder = match ($categorySlug) {
                    'nam'     => 'Watch/Watch_nam',
                    'cap-doi' => 'Watch/Watch_cap',
                    default   => 'Watch/Watch_nu',
                };

                $cart[$key] = [
                    'id'       => $product->id,
                    'name'     => $product->name,
                    'price'    => $product->price,
                    'image'    => $product->image,
                    'folder'   => $folder,
                    'category' => optional($product->category)->name ?? '',
                    'quantity' => $quantity,
                    'type'     => 'product',
                ];
            } else {
                $modelClass = match ($type) {
                    'straps'  => \App\Models\WatchStrap::class,
                    'boxes'   => \App\Models\WatchBox::class,
                    'glasses' => \App\Models\WatchGlass::class,
                    default   => null,
                };

                if (!$modelClass) {
                    return response()->json(['success' => false, 'message' => 'Loại phụ kiện không hợp lệ'], 422);
                }

                $item = $modelClass::findOrFail($id);

                $cart[$key] = [
                    'id'       => $item->id,
                    'name'     => $item->name,
                    'price'    => $item->price,
                    'image'    => $item->image,
                    'folder'   => 'accessories/' . $type,
                    'quantity' => $quantity,
                    'type'     => $type,
                ];
            }
        }

        session()->put('cart', $cart);
        $totals = $this->totals($cart);

        return response()->json([
            'success'    => true,
            'cart_count' => $totals['total_qty'],
            'total_qty'  => $totals['total_qty'],
            'cart_total' => $totals['cart_total'],
        ]);
    }

    // XÓA ITEM – Trả JSON cho AJAX, redirect khi truy cập thường
    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        $totals = $this->totals($cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'total_qty'  => $totals['total_qty'],
                'cart_total' => $totals['cart_total'],
                'cart_count' => $totals['total_qty'],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng');
    }

    // CẬP NHẬT SL – hỗ trợ cả quantity & qty, trả JSON số thuần
    public function update(Request $request)
    {
        $rowId    = $request->input('row_id');
        $quantity = (int) ($request->input('quantity', $request->input('qty')));

        if (!$rowId || $quantity < 1) {
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ'], 422);
        }

        $cart = session()->get('cart', []);
        if (!isset($cart[$rowId])) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        $cart[$rowId]['quantity'] = $quantity;
        session()->put('cart', $cart);

        $rowPrice    = $cart[$rowId]['price'];
        $rowSubtotal = $rowPrice * $cart[$rowId]['quantity'];
        $totals      = $this->totals($cart);

        return response()->json([
            'success'     => true,
            // số thuần (để JS định dạng)
            'subtotal'    => $rowSubtotal,
            'cart_total'  => $totals['cart_total'],
            'total_qty'   => $totals['total_qty'],
            'cart_count'  => $totals['total_qty'],

            // nếu bạn còn JS cũ dùng các key này thì vẫn tương thích:
            'rowSubtotal' => number_format($rowSubtotal, 0, ',', '.'),
            'cartTotal'   => number_format($totals['cart_total'], 0, ',', '.'),
            'totalQty'    => $totals['total_qty'],
            'ok'          => true,
        ]);
    }

    // ===== Helpers =====
    private function totals(array $cart): array
    {
        $totalQty  = 0;
        $cartTotal = 0;

        foreach ($cart as $it) {
            $q         = (int) ($it['quantity'] ?? 0);
            $totalQty += $q;
            $cartTotal += ($it['price'] ?? 0) * $q;
        }

        return ['total_qty' => $totalQty, 'cart_total' => $cartTotal];
    }
}
