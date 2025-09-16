<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'phone',
        'address',
        'total_price',
        'status',
    ];

    // Liên kết với bảng users
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với bảng order_items (nếu có)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Quan hệ với bảng payments (nếu có)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
