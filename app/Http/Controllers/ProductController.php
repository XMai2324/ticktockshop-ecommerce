<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;
use App\Models\Order;

class ProductController extends Controller
{
    // ================== LỌC SẢN PHẨM ==================
    public function filterProducts(Request $request)
    {
        $categories = Category::all();
        $brands     = Brand::all();
        $categorySlug = $request->input('category');
        $brandSlug    = $request->input('brand');
        $sort         = $request->input('sort');
        $priceRange   = $request->input('price_range');
        $keyword      = $request->input('keyword');


        $query = Product::with(['ratings', 'brand', 'category'])
        ->where('is_hidden', false);

        $currentCategory = null;
        $currentBrand    = null;

        // ===== Lọc theo Category =====
        if ($categorySlug) {
            $currentCategory = $categories->first(fn($cat) => Str::slug($cat->name) === $categorySlug);
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            }
        }

        // ===== Lọc theo Brand =====
        if ($brandSlug) {
            $currentBrand = $brands->first(fn($br) => Str::slug($br->name) === $brandSlug);
            if ($currentBrand) {
                $query->where('brand_id', $currentBrand->id);
            }
        }

        // ===== Lọc theo từ khóa (brand + category + tên SP) =====
        if ($keyword) {
            $kw          = trim($keyword);
            $slugKeyword = Str::slug(Str::ascii(mb_strtolower($kw))); // "rolex-cap"
            $tokens      = array_filter(explode('-', $slugKeyword));  // ["rolex","cap"]

            $brandIdsFromKeyword    = [];
            $categoryIdsFromKeyword = [];
            $leftoverTokens         = [];

            foreach ($tokens as $token) {
                $matched = false;

                // --- Thử khớp BRAND ---
                foreach ($brands as $brand) {
                    $brandSlug = Str::slug(Str::ascii(mb_strtolower($brand->name))); // "rolex"

                    // token xuất hiện trong slug brand (vd: "role" cũng được, nhưng trường hợp bạn là "rolex")
                    if (Str::contains($brandSlug, $token)) {
                        $brandIdsFromKeyword[] = $brand->id;
                        if (!$currentBrand) {
                            $currentBrand = $brand;
                        }
                        $matched = true;
                        break;
                    }
                }

                if ($matched) {
                    continue;
                }

                // --- Thử khớp CATEGORY ---
                foreach ($categories as $category) {
                    $catSlug  = Str::slug(Str::ascii(mb_strtolower($category->name))); // "cap-doi"
                    $catWords = explode('-', $catSlug);                                 // ["cap","doi"]

                    // token khớp 1 từ trong slug, vd "cap" khớp "cap-doi"
                    if (in_array($token, $catWords, true) || Str::contains($catSlug, $token)) {
                        $categoryIdsFromKeyword[] = $category->id;
                        if (!$currentCategory) {
                            $currentCategory = $category;
                        }
                        $matched = true;
                        break;
                    }
                }

                // Không phải brand / category -> dùng để search theo tên sản phẩm
                if (!$matched) {
                    $leftoverTokens[] = $token;
                }
            }

            $brandIdsFromKeyword    = array_unique($brandIdsFromKeyword);
            $categoryIdsFromKeyword = array_unique($categoryIdsFromKeyword);

            // ❗ LUÔN siết brand nếu keyword có brand (bất kể URL có ?brand hay không)
            if (!empty($brandIdsFromKeyword)) {
                $query->whereIn('brand_id', $brandIdsFromKeyword);
            }

            // ❗ LUÔN siết category nếu keyword có category
            if (!empty($categoryIdsFromKeyword)) {
                $query->whereIn('category_id', $categoryIdsFromKeyword);
            }

            // Phần còn lại của keyword -> tìm trong tên sản phẩm
            if (!empty($leftoverTokens)) {
                $query->where(function ($q) use ($leftoverTokens) {
                    foreach ($leftoverTokens as $tk) {
                        $q->where('name', 'like', '%' . $tk . '%');
                    }
                });
            }
        }   
        // ===== Lọc theo khoảng giá =====
        if ($priceRange && str_contains($priceRange, '-')) {
            [$min, $max] = explode('-', $priceRange, 2);
            $query->whereBetween('price', [(int) $min, (int) $max]);
        }

        // ===== Sắp xếp =====
        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        }

        // LẤY SẢN PHẨM Ở ĐÂY
        $products = $query->paginate(8);

        return view('client.products', [
            'products'           => $products,
            'categories'         => $categories,
            'brands'             => $brands,
            'currentCategory'    => $currentCategory,
            'currentBrand'       => $currentBrand,
            'selectedSort'       => $sort,
            'selectedPriceRange' => $priceRange,
            'keyword'            => $keyword,
        ]);
    }


    // ================== HIỂN THỊ THEO DANH MỤC ==================
    public function byCategory(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Product::with(['brand', 'category'])
            ->where('category_id', $category->id)
            ->where('is_hidden', false);

        // Lọc theo khoảng giá
        $priceRange = $request->input('price_range');
        if ($priceRange && preg_match('/^\d+\-\d+$/', $priceRange)) {
            [$min, $max] = array_map('intval', explode('-', $priceRange, 2));
            if ($min <= $max) {
                $query->whereBetween('price', [$min, $max]);
            }
        }

        // Sắp xếp
        $sort = $request->input('sort');
        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest('id');
        }

        $products = $query->paginate(12);

        return view('client.products', [
            'products'           => $products,
            'categories'         => Category::all(),
            'brands'             => Brand::all(),
            'currentCategory'    => $category,
            'currentBrand'       => null,
            'selectedSort'       => $sort,
            'selectedPriceRange' => $priceRange,
            'keyword'            => null,
        ]);
    }

    // ================== TRANG CHI TIẾT SẢN PHẨM ==================
    public function showDetail(Product $product)
    {
        $product->load(['brand', 'category']);

        // Lấy sản phẩm liên quan
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_hidden', false)
            ->latest('id')
            ->take(8)
            ->get();

        return view('client.products.detail', compact('product', 'related'));
    }

    // ================== ADMIN INDEX ==================
    public function index(Request $request)
    {
        $brands     = Brand::all();
        $categories = Category::all();

        $products = Product::with('brand');

        if ($request->has('brand')) {
            $products->where('brand_id', $request->brand);
        }

        return view('admin.products_index', [
            'products'   => $products->get(),
            'brands'     => $brands,
            'categories' => $categories,
        ]);
    }

    // ================== STORE ==================
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'brand_id'    => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'movement'         => 'nullable|string|max:255',
            'case_material'    => 'nullable|string|max:255',
            'strap_material'   => 'nullable|string|max:255',
            'glass_material'   => 'nullable|string|max:255',
            'diameter'         => 'nullable|string|max:255',
            'water_resistance' => 'nullable|string|max:255',
            'image'       => 'required|mimetypes:image/jpeg,image/png,image/webp,image/avif|max:4096',
        ]);

        // Lấy thư mục lưu ảnh theo danh mục
        $category = Category::findOrFail($request->category_id);
        $slugCat  = Str::slug($category->name);

        $folder = match ($slugCat) {
            'nam'     => 'Watch/Watch_nam',
            'nu'      => 'Watch/Watch_nu',
            'cap-doi' => 'Watch/Watch_cap',
            default   => 'Watch/Watch_nu',
        };

        // Tạo tên file đẹp
        $originalName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $request->file('image')->getClientOriginalExtension();
        $imageName    = Str::slug($originalName) . '.' . $extension;

        // Lưu file vào storage/app/public/...
        $request->file('image')->storeAs('public/' . $folder, $imageName);

        // Tạo slug không trùng
        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $i = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        // Tạo sản phẩm
        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'quantity'    => 100,
            'brand_id'    => $request->brand_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image'       => $imageName,
            'slug'        => $productSlug,
            'is_hidden'   => false, // hoặc 0
            'movement'         => $request->movement,
            'case_material'    => $request->case_material,
            'strap_material'   => $request->strap_material,
            'glass_material'   => $request->glass_material,
            'diameter'         => $request->diameter,
            'water_resistance' => $request->water_resistance,
            'slug'        => $slug,
            'is_hidden'   => true,   // mặc định ẩn
            'is_new'      => true,   // gắn nhãn NEW
        ]);

        return redirect()->route('admin.products_index')
                        ->with('success', 'Thêm sản phẩm mới thành công!');
    }


    // ================== UPDATE ==================
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'brand_id'    => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'movement'         => $request->movement,
            'case_material'    => $request->case_material,
            'strap_material'   => $request->strap_material,
            'glass_material'   => $request->glass_material,
            'diameter'         => $request->diameter,
            'water_resistance' => $request->water_resistance,
            // 'image'       => 'nullable|image|mimes:jpg,jpeg,png,avif,webp|max:2048',
            'image' => 'nullable|mimetypes:image/jpeg,image/png,image/webp,image/avif|max:4096',
        ]);

        $product->fill([
            'name'        => $request->name,
            'price'       => $request->price,
            'brand_id'    => $request->brand_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'slug'        => Str::slug($request->name),
            'movement'         => $request->movement,
            'case_material'    => $request->case_material,
            'strap_material'   => $request->strap_material,
            'glass_material'   => $request->glass_material,
            'diameter'         => $request->diameter,
            'water_resistance' => $request->water_resistance,
        ]);

        if ($request->hasFile('image')) {
            $category = Category::findOrFail($request->category_id);
            $slugCat  = Str::slug($category->name);

            $folder = match ($slugCat) {
                'nam'     => 'Watch/Watch_nam',
                'cap-doi' => 'Watch/Watch_cap',
                default   => 'Watch/Watch_nu',
            };

            $imageName = $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public/' . $folder, $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('admin.products_index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    // ================== DESTROY ==================
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $categorySlug = Str::slug($product->category->name ?? '');
        $folder = match ($categorySlug) {
            'nam'     => 'Watch/Watch_nam',
            'cap-doi' => 'Watch/Watch_cap',
            default   => 'Watch/Watch_nu',
        };

        $imagePath = 'public/' . $folder . '/' . $product->image;
        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        $product->delete();

        return redirect()->route('admin.products_index')->with('success', 'Đã xoá sản phẩm thành công!');
    }


    public function toggleHidden($id)
    {
        $product = Product::findOrFail($id);

        // Đảo trạng thái
        $product->is_hidden = ! $product->is_hidden;
        $product->save();

        return response()->json([
            'hidden' => $product->is_hidden,
        ]);
    }


    
}