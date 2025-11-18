<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        // 'name',
        // 'description',
        // 'price',
        // 'image',
        // 'category_id',
        // 'brand_id',
        // 'warranty_months',
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
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    protected $casts = [
        'is_hidden' => 'boolean',
    ];
}
