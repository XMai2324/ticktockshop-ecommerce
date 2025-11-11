<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'brand_id',
        'warranty_month',
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
}
