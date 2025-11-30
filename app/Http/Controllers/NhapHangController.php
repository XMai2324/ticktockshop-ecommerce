<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\ImportHistory;

class NhapHangController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        $products = $query->get();

        return view('admin.nhapHang_index', [
            'products'   => $products,
            'categories' => Category::all(),
            'brands'     => Brand::all(),
        ]);
    }

    // Lưu tạm để xem trước
    public function savePreview(Request $request)
    {
        $items = $request->input('products', []);
        $changed = [];

        foreach ($items as $id => $p) {
            $quantityAdd = intval($p['quantity_add'] ?? 0);
            $isNew = Str::startsWith($id, 'new');

            $product = null;
            if (!$isNew) {
                $product = Product::find($id);
            }

            // Chỉ lưu các sản phẩm mới hoặc có thay đổi số lượng
            if ($isNew || $quantityAdd > 0 || ($product && floatval($p['cost_price'] ?? 0) != $product->cost_price)) {

                // Validate cơ bản
                if (empty($p['name'])) {
                    return back()->with('warning', "Sản phẩm tại dòng {$id} chưa có tên.");
                }
                if ($isNew) {
                    if (empty($p['category_id']) || empty($p['brand_id'])) {
                        return back()->with('warning', "Sản phẩm mới '{$p['name']}' chưa chọn danh mục hoặc thương hiệu.");
                    }
                }
                if (isset($p['cost_price']) && floatval($p['cost_price']) < 0) {
                    return back()->with('warning', "Giá nhập không được âm cho sản phẩm '{$p['name']}'.");
                }
                if ($quantityAdd < 0) {
                    return back()->with('warning', "Số lượng thêm không được âm cho sản phẩm '{$p['name']}'.");
                }

                // Thêm vào mảng preview
                $changed[$id] = $p;

                // Thêm thông tin hiển thị tên danh mục, thương hiệu
                $changed[$id]['category_name'] = isset($p['category_id']) ? Category::find($p['category_id'])->name ?? '' : ($p['category_name'] ?? '');
                $changed[$id]['brand_name']    = isset($p['brand_id']) ? Brand::find($p['brand_id'])->name ?? '' : ($p['brand_name'] ?? '');
            }
        }
        // Dùng để lưu toàn bộ price mới.
        // $items = $request->input('products', []);
        // $changed = [];

        // foreach ($items as $id => $p) {
        //     // Validate cơ bản
        //     if (empty($p['name'])) {
        //         return back()->with('warning', "Sản phẩm tại dòng {$id} chưa có tên.");
        //     }
        //     if (Str::startsWith($id, 'new')) {
        //         if (empty($p['category_id']) || empty($p['brand_id'])) {
        //             return back()->with('warning', "Sản phẩm mới '{$p['name']}' chưa chọn danh mục hoặc thương hiệu.");
        //         }
        //     }
        //     if (isset($p['cost_price']) && floatval($p['cost_price']) < 0) {
        //         return back()->with('warning', "Giá nhập không được âm cho sản phẩm '{$p['name']}'.");
        //     }
        //     if (isset($p['quantity_add']) && intval($p['quantity_add']) < 0) {
        //         return back()->with('warning', "Số lượng thêm không được âm cho sản phẩm '{$p['name']}'.");
        //     }

        //     $changed[$id] = $p;

        //     // Thêm tên danh mục, thương hiệu cho preview
        //     if (isset($p['category_id'])) {
        //         $changed[$id]['category_name'] = \App\Models\Category::find($p['category_id'])->name ?? '';
        //     }
        //     if (isset($p['brand_id'])) {
        //         $changed[$id]['brand_name'] = \App\Models\Brand::find($p['brand_id'])->name ?? '';
        //     }
        // }

        if (empty($changed)) {
            return back()->with('warning', 'Không có sản phẩm nào thay đổi.');
        }

        Session::put('nhap_hang_preview', $changed);

        return redirect()->route('admin.nhapHang_preview');
    }

    // Trang xem trước
    public function preview()
    {
        $changed = Session::get('nhap_hang_preview', []);

        if (empty($changed)) {
            return redirect()->route('admin.nhapHang_index')->with('warning', 'Không có dữ liệu để xem trước.');
        }

        return view('admin.nhapHang_preview', compact('changed'));
    }

    // Xác nhận và lưu vào DB
    // public function confirmPreview(Request $request)
    // {
    //     $changed = Session::get('nhap_hang_preview', []);

    //     if (empty($changed)) {
    //         return redirect()->route('admin.nhapHang_index')->with('warning', 'Không có dữ liệu để lưu.');
    //     }

    //     foreach ($changed as $id => $p) {
    //         try {
    //             $product = Str::startsWith($id, 'new') ? new Product() : Product::find($id);
    //             if (!$product) continue;

    //             // Tên, danh mục, thương hiệu
    //             $product->name        = $p['name'] ?? $product->name;
    //             $product->category_id = $p['category_id'] ?? $product->category_id;
    //             $product->brand_id    = $p['brand_id'] ?? $product->brand_id;

    //             // Tạo slug tự động từ tên
    //             $baseSlug = Str::slug($product->name ?: 'product');
    //             $slug = $baseSlug;
    //             $i = 1;
    //             while (Product::where('slug', $slug)->where('id', '<>', $product->id ?? 0)->exists()) {
    //                 $slug = $baseSlug . '-' . $i++;
    //             }
    //             $product->slug = $slug;

    //             // Giá nhập / giá bán
    //             $product->cost_price  = max(0, floatval($p['cost_price'] ?? 0));
    //             $product->price = round($product->cost_price * 1.2 / 1000) * 1000; // làm tròn lên nghìn gần nhất

    //             // Số lượng
    //             $quantityAdd = intval($p['quantity_add'] ?? 0);
    //             $product->quantity = ($product->quantity ?? 0) + $quantityAdd;

    //             // Ảnh
    //             // if ($request->hasFile("products.$id.image")) {
    //             //     $file = $request->file("products.$id.image");
    //             //     if ($file->isValid()) {
    //             //         $product->image = $file->store('products', 'public');
    //             //     }
    //             // }

    //             $product->save();
    //         } catch (\Throwable $e) {
    //             Log::error("Lỗi lưu sản phẩm id={$id}: " . $e->getMessage());
    //         }
    //     }

    //     // Xóa session sau khi lưu
    //     Session::forget('nhap_hang_preview');

    //     return redirect()->route('admin.nhapHang_index')->with('success', 'Đã lưu nhập hàng!');
    // }

    public function confirmPreview(Request $request)
    {
        $changed = Session::get('nhap_hang_preview', []);

        if (empty($changed)) {
            return redirect()->route('admin.nhapHang_index')->with('warning', 'Không có dữ liệu để lưu.');
        }

        foreach ($changed as $id => $p) {
            try {
                $product = Str::startsWith($id, 'new') ? new Product() : Product::find($id);
                if (!$product) continue;

                // --- Lấy giá trị trước ---
                $quantityBefore  = $product->quantity ?? 0;
                $costBefore      = $product->cost_price ?? 0;

                // --- Cập nhật sản phẩm ---
                $product->name        = $p['name'];
                $product->category_id = $p['category_id'];
                $product->brand_id    = $p['brand_id'];

                // Slug
                $baseSlug = Str::slug($product->name);
                $slug = $baseSlug;
                $i=1;
                while (Product::where('slug', $slug)->where('id','<>',$product->id ?? 0)->exists()) {
                    $slug = $baseSlug.'-'.$i++;
                }
                $product->slug = $slug;

                // Giá nhập + giá bán
                $product->cost_price = max(0, floatval($p['cost_price'] ?? 0));
                $product->price = round($product->cost_price * 1.2 / 1000) * 1000;

                // Số lượng
                $quantityAdd = intval($p['quantity_add'] ?? 0);
                $product->quantity = $quantityBefore + $quantityAdd;

                $product->save();

                // --- Lưu lịch sử ---
                ImportHistory::create([
                    'product_id'        => $product->id,
                    'quantity_before'   => $quantityBefore,
                    'quantity_added'    => $quantityAdd,
                    'quantity_after'    => $product->quantity,

                    'cost_price_before' => $costBefore,
                    'cost_price_after'  => $product->cost_price,

                    'sell_price_after'  => $product->price
                ]);

            } catch (\Throwable $e) {
                Log::error("Lỗi lưu sản phẩm id={$id}: " . $e->getMessage());
            }
        }

        Session::forget('nhap_hang_preview');

        return redirect()->route('admin.nhapHang_index')->with('success', 'Đã lưu nhập hàng!');
    }

    // public function confirmPreview(Request $request)
    // {
    //     $changed = Session::get('nhap_hang_preview', []);

    //     if (empty($changed)) {
    //         return redirect()->route('admin.nhapHang_index')->with('warning', 'Không có dữ liệu để lưu.');
    //     }

    //     foreach ($changed as $id => $p) {
    //         try {

    //             // ✅ Xác định đây có phải là sản phẩm mới (id dạng "new_xxx")
    //             $isNew = Str::startsWith($id, 'new');

    //             // ✅ Nếu là sp mới -> new Product(), nếu không -> find theo id
    //             $product = $isNew ? new Product() : Product::find($id);
    //             if (!$product) continue;

    //             // Tên, danh mục, thương hiệu
    //             $product->name        = $p['name'] ?? $product->name;
    //             $product->category_id = $p['category_id'] ?? $product->category_id;
    //             $product->brand_id    = $p['brand_id'] ?? $product->brand_id;

    //             // Tạo slug tự động từ tên, đảm bảo không trùng
    //             $baseSlug = Str::slug($product->name ?: 'product');
    //             $slug = $baseSlug;
    //             $i = 1;
    //             while (Product::where('slug', $slug)
    //                         ->when(!$isNew, fn($q) => $q->where('id', '<>', $product->id))
    //                         ->exists()) {
    //                 $slug = $baseSlug . '-' . $i++;
    //             }
    //             $product->slug = $slug;

    //             // Giá nhập / giá bán
    //             $product->cost_price  = max(0, floatval($p['cost_price'] ?? 0));
    //             $product->price       = round($product->cost_price * 1.2 / 1000) * 1000; // làm tròn lên nghìn gần nhất

    //             // Số lượng (nếu sp mới thì quantity cũ là null -> coi như 0)
    //             $quantityAdd = intval($p['quantity_add'] ?? 0);
    //             $product->quantity = ($product->quantity ?? 0) + $quantityAdd;

    //             // ✅ Nếu là sản phẩm mới -> mặc định ẨN
    //             if ($isNew) {
    //                 $product->is_hidden = true;
    //             }

    //             $product->save();

    //         } catch (\Throwable $e) {
    //             Log::error("Lỗi lưu sản phẩm id={$id}: " . $e->getMessage());
    //         }
    //     }

    //     // Xóa session sau khi lưu
    //     Session::forget('nhap_hang_preview');

    //     return redirect()->route('admin.nhapHang_index')->with('success', 'Đã lưu nhập hàng!');
    // }

    // Xuất phiếu CSV đơn giản
    public function exportPreview()
    {
        $changed = Session::get('nhap_hang_preview', []);
        if (empty($changed)) {
            return redirect()->route('admin.nhapHang_index')->with('warning', 'Không có dữ liệu để xuất.');
        }

        $filename = 'phieu_nhap_hang_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($changed) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Ten SP', 'Danh muc', 'Thuong hieu', 'Gia nhap', 'Gia ban', 'So luong them']);

            foreach ($changed as $p) {
                fputcsv($file, [
                    $p['name'] ?? '',
                    $p['category_name'] ?? '',
                    $p['brand_name'] ?? '',
                    $p['cost_price'] ?? 0,
                    round(($p['cost_price'] ?? 0) * 1.2 / 1000) * 1000,
                    $p['quantity_add'] ?? 0,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
