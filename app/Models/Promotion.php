<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Promotion extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'max_discount',
        'min_order_value',
        'usage_limit',
        'per_user_limit',
        'used_count',
        'is_active',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

     public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
