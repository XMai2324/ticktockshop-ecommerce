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
        $query = Product::with('ratings');
        $categorySlug = $request->input('category');
        $brandSlug    = $request->input('brand');
        $sort         = $request->input('sort');
        $priceRange   = $request->input('price_range');
        $keyword      = $request->input('keyword');

        // CHỖ NÀY: KHỞI TẠO QUERY CÓ is_hidden = false
        $query = Product::query()->where('is_hidden', false);

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

        // ===== Lọc theo từ khóa =====
        if ($keyword) {
            $slugKeyword = Str::slug(Str::ascii(mb_strtolower($keyword)));

            $allBrands = $brands->keyBy(fn($b) => Str::slug(Str::ascii(mb_strtolower($b->name))));
            $allCategories = $categories->keyBy(fn($c) => Str::slug(Str::ascii(mb_strtolower($c->name))));

            foreach ($allBrands as $slug => $brand) {
                if (Str::contains($slugKeyword, $slug)) {
                    $currentBrand = $brand;
                    $query->where('brand_id', $brand->id);
                    break;
                }
            }

            foreach ($allCategories as $slug => $category) {
                $slugWords = explode('-', $slug);
                foreach ($slugWords as $word) {
                    if (Str::contains($slugKeyword, $word)) {
                        $currentCategory = $category;
                        $query->where('category_id', $category->id);
                        break 2;
                    }
                }
            }

            if (!$currentBrand && !$currentCategory) {
                $query->where('name', 'like', '%' . $keyword . '%');
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