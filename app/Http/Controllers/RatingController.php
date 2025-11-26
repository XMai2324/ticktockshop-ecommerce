<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    //Danh sách đánh giá cho một sản phẩm
    public function index(Product $product)
    {
        $ratings = $product->ratings()->with('user:id,name')->latest()->get();

        return response()->json(['data' => $ratings]);
    }

   // Tạo đánh giá mới cho sản phẩm
    public function store(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Nếu người dùng đã đánh giá, thì cập nhất đánh giá đó.
        $existing = Rating::where('user_id', $user->id)->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->rating = $request->input('rating');
            $existing->comment = $request->input('comment');
            $existing->save();

            return response()->json(['message' => 'Rating updated', 'data' => $existing]);
        }

        // Tạo đánh giá mới
        $rating = Rating::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        return response()->json(['message' => 'Rating created', 'data' => $rating], 201);
    }

    /**
     * Cập nhật một đánh giá (chỉ chủ sở hữu)
     */
    public function update(Request $request, Rating $rating)
    {
        $user = Auth::user();
        if (!$user || $rating->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
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

        return response()->json(['message' => 'Rating updated', 'data' => $rating]);
    }

// Xóa đánh giá (chỉ chủ sở hữu hoặc admin)

    public function destroy(Request $request, $id)
    {
        $rating = Rating::findOrFail($id);

        // nếu có kiểm tra quyền, đảm bảo admin được phép
        // if (auth()->id() !== $rating->user_id && !auth()->user()->isAdmin()) {
        //     abort(403);
        // }

        $rating->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Deleted'], 200);
        }

        return redirect()->back()->with('success', 'Đã xóa đánh giá.');
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
        // if null retuen all
        return response()->json($ratings->get());
    }
}
