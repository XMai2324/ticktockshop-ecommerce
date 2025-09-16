<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function filterProducts(Request $request)
    {
        $categories = Category::all();
        $brands = Brand::all();

        $categorySlug = $request->input('category');
        $brandSlug = $request->input('brand');
        $sort = $request->input('sort');
        $priceRange = $request->input('price_range');
        $keyword = $request->input('keyword');

        $query = Product::query();

        // Khai báo mặc định để tránh lỗi
        $currentCategory = null;
        $currentBrand = null;

        // ✅ Lọc theo từ khóa thông minh (brand + category)
        if ($keyword) {
            $slugKeyword = Str::slug(Str::ascii(mb_strtolower($keyword))); // chuẩn hóa từ khóa tìm

            $allBrands = $brands->keyBy(fn($b) => Str::slug(Str::ascii(mb_strtolower($b->name))));
            $allCategories = $categories->keyBy(fn($c) => Str::slug(Str::ascii(mb_strtolower($c->name))));

            $matchedBrand = null;
            $matchedCategory = null;

            foreach ($allBrands as $slug => $brand) {
                if (Str::contains($slugKeyword, $slug)) {
                    $matchedBrand = $brand;
                    break;
                }
            }

            foreach ($allCategories as $slug => $category) {
                $slugWords = explode('-', $slug);
                foreach ($slugWords as $word) {
                    if (Str::contains($slugKeyword, $word)) {
                        $matchedCategory = $category;
                        break 2;
                    }
                }
            }

            if ($matchedBrand) {
                $currentBrand = $matchedBrand;
                $query->where('brand_id', $matchedBrand->id);
            }

            if ($matchedCategory) {
                $currentCategory = $matchedCategory;
                $query->where('category_id', $matchedCategory->id);
            }

            // fallback nếu brand/category không khớp
            if (!$matchedBrand && !$matchedCategory) {
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
        if ($priceRange) {
            [$min, $max] = explode('-', $priceRange);
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
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentCategory' => $currentCategory,
            'currentBrand' => $currentBrand,
            'selectedSort' => $sort,
            'selectedPriceRange' => $priceRange,
            'keyword' => $keyword,
        ]);
    }



    public function quickView($slug)
    {
        $product = Product::where('slug', $slug)->with('category', 'brand')->firstOrFail();
        return view('client.products.quick_view', compact('product'));
    }
}