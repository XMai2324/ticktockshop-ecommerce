<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        // Giả sử lấy giỏ hàng từ session
       $cartItems = collect(session()->get('cart', []));

        $cartTotal = $cartItems->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('client.cart', compact('cartItems', 'cartTotal'));
    }

   public function addToCart(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);
        $key = $type . '_' . $id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            if ($type === 'product') {
                $product = \App\Models\Product::with('category')->findOrFail($id);

                $categorySlug = \Str::slug(optional($product->category)->name ?? '');
                $folder = match ($categorySlug) {
                    'nam' => 'Watch/Watch_nam',
                    'cap-doi' => 'Watch/Watch_cap',
                    default => 'Watch/Watch_nu',
                };

                $cart[$key] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'folder' => $folder,
                    'category' => optional($product->category)->name ?? '',
                    'quantity' => $quantity,
                    'type' => 'product',
                ];
            } else {
                $modelClass = match ($type) {
                    'straps' => \App\Models\WatchStrap::class,
                    'boxes' => \App\Models\WatchBox::class,
                    'glasses' => \App\Models\WatchGlass::class,
                    default => null,
                };

                if (!$modelClass) {
                    return response()->json(['success' => false, 'message' => 'Loại phụ kiện không hợp lệ']);
                }

                $item = $modelClass::findOrFail($id);

                $cart[$key] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'image' => $item->image,
                    'folder' => 'accessories/' . $type,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => array_sum(array_column(session('cart'), 'quantity')),
        ]);
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng');
    }

}