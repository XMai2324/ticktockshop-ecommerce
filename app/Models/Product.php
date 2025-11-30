<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'image',
        'images',
        'category_id',
        'brand_id',
        'warranty_months',
        'is_hidden',
        'movement',
        'case_material',
        'strap_material',
        'glass_material',
        'diameter',
        'water_resistance',
        'is_new',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAvgRatingAttribute()
    {
        return round($this->ratings()->avg('rating'), 1); // 4.5
    }

    public function getRatingCountAttribute()
    {
        return $this->ratings()->count(); // số lượng đánh giá
    }

    public function getIsNewAttribute()
    {
        return $this->created_at >= now()->subDays(7);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope: lấy sản phẩm bán chạy theo DOANH THU
     * - revenue = SUM(quantity * price) từ bảng order_items
     * - chỉ tính các order có status = 'completed' (bạn đổi lại theo hệ thống của bạn)
     */
    public function scopeBestSellerByRevenue($query, $limit = 10)
    {
        return $query
            ->with(['brand', 'category', 'ratings'])
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'delivered') // hoặc 'confirmed' tuỳ bạn dùng
            ->select(
                'products.id',
                'products.name',
                'products.slug',
                'products.image',
                'products.price',
                'products.brand_id',
                'products.category_id'
            )
            ->selectRaw('SUM(order_items.quantity) AS total_sold')
            ->selectRaw('SUM(order_items.quantity * order_items.price) AS revenue')
            ->groupBy(
                'products.id',
                'products.name',
                'products.slug',
                'products.image',
                'products.price',
                'products.brand_id',
                'products.category_id'
            )
            ->orderByDesc('revenue')
            ->take($limit);
    }
}
