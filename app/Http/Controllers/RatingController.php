<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class RatingController extends Controller
{
    //Danh sách đánh giá cho một sản phẩm
    public function index(Product $product)
    {
        $ratings = $product->ratings()->with('user:id,name')->latest()->get();

        return response()->json(['data' => $ratings]);
    }

   // Tạo đánh giá mới cho sản phẩm
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Vui lòng đăng nhập để đánh giá.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'order_id'   => 'required|exists:orders,id', // Bắt buộc có đơn hàng
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::find($request->order_id);

        // A. Kiểm tra quyền sở hữu đơn hàng
        if ($order->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Bạn không có quyền đánh giá đơn hàng này.');
        }
        // B. Kiểm tra trạng thái đơn hàng (chỉ cho đánh giá nếu đã giao hàng)
        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Bạn chỉ có thể đánh giá khi đã nhận được hàng.');
        }

        // C. Kiểm tra sản phẩm có nằm trong đơn hàng đó không? (Tránh hack ID sản phẩm)
        // Yêu cầu model Order có quan hệ orderItems()
        $hasProduct = $order->orderItems()->where('product_id', $request->product_id)->exists();
        if (!$hasProduct) {
            return response()->json(['message' => 'Sản phẩm này không có trong đơn hàng của bạn.'], 400);
        }

        // D. Kiểm tra đã đánh giá chưa (Tránh spam)
        $existing = Rating::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->first();

        if ($existing) {
            // Nếu muốn cho phép sửa đánh giá cũ:
            $existing->update([
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            return redirect()->back()->with('success', 'Đã cập nhật đánh giá thành công!');
        }

        // Tạo đánh giá mới
       Rating::create([
            'user_id'    => $user->id,
            'product_id' => $request->product_id,
            'order_id'   => $request->order_id,
            'rating'     => $request->input('rating'),
            'comment'    => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    /**
     * Cập nhật một đánh giá (chỉ chủ sở hữu)
     */
    public function update(Request $request, $id)
    {
        $rating = Rating::find($id);

        if (!$rating) {
            return response()->json(['message' => 'Không tìm thấy đánh giá.'], 404);
        }

        // Kiểm tra quyền chủ sở hữu
        if (Auth::id() !== $rating->user_id) {
            return response()->json(['message' => 'Bạn không có quyền sửa đánh giá này.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rating->fill($request->only(['rating', 'comment']));
        $rating->save();

        return response()->json(['message' => 'Cập nhật thành công.', 'data' => $rating]);
    }

// Xóa đánh giá (chỉ chủ sở hữu hoặc admin)

   public function destroy(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);
        $user = Auth::user();
        if ($user->id !== $rating->user_id) {
             return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }
        $rating->delete();
        return redirect()->back()->with('success', 'Đã xóa đánh giá thành công!');
    }

    // Thêm: trang quản lý ratings cho admin
    public function adminIndex()
    {
        $ratings = Rating::with(['user:id,name','product:id,name'])
            ->latest()
            ->get();
        return view('admin.ratings_index', compact('ratings'));
    }

    // Thêm: lưu phản hồi từ admin
    public function updateResponse(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);
        $rating->response = $request->response;
        $rating->save();

        return response()->json(['message' => 'Đã lưu phản hồi thành công!']);
    }


    // Thêm: API JSON trả dữ liệu cho JS (admin)
    public function data(\Illuminate\Http\Request $request)
    {

        $ratings = Rating::with(['user:id,name','product:id,name'])
            ->latest();
        if($request->rating){
            $ratings->where('rating', $request->rating);
        }

        if ($request->response !== null && $request->response !== "") {
            if ($request->response == "1") {
                $ratings->whereNotNull('response');
            } else {
                $ratings->whereNull('response');
            }
        }

        if ($request->date_from) {
            $ratings->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $ratings->whereDate('created_at', '<=', $request->date_to);
        }
        // if null return all
        return response()->json($ratings->get());
    }
}
