<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'warranty_code',
        'start_date',
        'end_date',
        'status',
    ];

    // Quan hệ: Mỗi bảo hành thuộc về 1 order item
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
