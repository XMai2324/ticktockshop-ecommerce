<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

        $query = Product::query();

        $currentCategory = null;
        $currentBrand    = null;

        // Nếu có category slug
        if ($categorySlug) {
            $currentCategory = $categories->first(function ($cat) use ($categorySlug) {
                return Str::slug($cat->name) === $categorySlug;
            });
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            }
        }

        // Nếu có brand slug
        if ($brandSlug) {
            $currentBrand = $brands->first(function ($br) use ($brandSlug) {
                return Str::slug($br->name) === $brandSlug;
            });
            if ($currentBrand) {
                $query->where('brand_id', $currentBrand->id);
            }
        }

        // Lọc theo từ khóa
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
        // Lọc theo category từ URL
        if ($categorySlug) {
            $currentCategory = $categories->first(function ($cat) use ($categorySlug) {
                return Str::slug($cat->name) === $categorySlug;
            });

            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            }
        }

        // Lọc theo brand từ URL
        if ($brandSlug) {
            $currentBrand = $brands->first(function ($br) use ($brandSlug) {
                return Str::slug($br->name) === $brandSlug;
            });

            if ($currentBrand) {
                $query->where('brand_id', $currentBrand->id);
            }
        }
        // Lọc theo khoảng giá
        if ($priceRange && str_contains($priceRange, '-')) {
            [$min, $max] = explode('-', $priceRange, 2);
            $query->whereBetween('price', [(int) $min, (int) $max]);
        }

        // Sắp xếp
        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        }

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

    // ================== HIỂN THỊ TOÀN BỘ THEO CATEGORY ==================
    public function byCategory(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Product::with(['brand','category'])
            ->where('category_id', $category->id);

        // Lọc theo khoảng giá (an toàn hơn với regex + intval)
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
            // mặc định mới nhất (nếu muốn)
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


    // ================== QUICK VIEW ==================
    public function quickView($slug)
    {
        $product = Product::where('slug', $slug)->with('category', 'brand')->firstOrFail();
        return view('client.products.quick_view', compact('product'));
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
            'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($request->category_id);
        $slug = Str::slug($category->name);

        $folder = match($slug) {
            'nam'     => 'Watch/Watch_nam',
            'nu'      => 'Watch/Watch_nu',
            'cap-doi' => 'Watch/Watch_cap',
            default   => 'Watch/Watch_nu',
        };

        $originalName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $request->file('image')->getClientOriginalExtension();
        $imageName    = Str::slug($originalName) . '.' . $extension;
        $request->file('image')->storeAs('public/' . $folder, $imageName);

        $productName = $request->name;
        $slug = Str::slug($productName);
        if (empty($slug)) {
            $slug = 'dong-ho-' . time();
        }

        Product::create([
            'name'        => $productName,
            'price'       => $request->price,
            'quantity'    => 100,
            'brand_id'    => $request->brand_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image'       => $imageName,
            'slug'        => $slug,
        ]);

        return redirect()->route('admin.products_index')->with('success', 'Thêm đồng hồ thành công!');
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
        ]);

        $product->name        = $request->name;
        $product->price       = $request->price;
        $product->brand_id    = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->slug        = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $category = Category::findOrFail($request->category_id);
            $slug     = Str::slug($category->name);

            $folder = match ($slug) {
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
}
