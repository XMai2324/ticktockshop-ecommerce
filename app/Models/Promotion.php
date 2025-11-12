<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order; // nếu mỗi đơn có 1 promotion_id

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','code','type','value','max_discount','min_order_value',
        'usage_limit','per_user_limit','used_count','is_active','start_at','end_at',
    ];

    protected $casts = [
        // Money and counters
        'value'           => 'float',
        'max_discount'    => 'float',
        'min_order_value' => 'float',
        'usage_limit'     => 'integer',
        'per_user_limit'  => 'integer',
        'used_count'      => 'integer',
        // Flags and dates
        'is_active' => 'boolean',
        'start_at'  => 'datetime',
        'end_at'    => 'datetime',
    ];

    /* Quan hệ: nếu orders có cột promotion_id */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /* Scopes */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeInWindow($q, $at = null)
    {
        $at = $at ?: now();
        return $q->where(function($qq) use ($at) {
                $qq->whereNull('start_at')->orWhere('start_at', '<=', $at);
            })
            ->where(function($qq) use ($at) {
                $qq->whereNull('end_at')->orWhere('end_at', '>=', $at);
            });
    }

    public function scopeMinOrderOk($q, $subtotal)
    {
        return $q->where(function($qq) use ($subtotal) {
            $qq->whereNull('min_order_value')->orWhere('min_order_value', '<=', $subtotal);
        });
    }

    public function scopeCode($q, $code)
    {
        return $q->where('code', strtoupper(trim((string)$code)));
    }

    /* Chuẩn hóa code khi lưu */
    protected static function booted()
    {
        static::saving(function (Promotion $model) {
            if ($model->code) {
                $model->code = strtoupper(trim($model->code));
            }
        });
    }

    /* Computed flags */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_at !== null && now()->gt($this->end_at);
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_at !== null && now()->lt($this->start_at);
    }

    public function getIsRunningAttribute(): bool
    {
        return $this->is_active && !$this->is_expired && !$this->is_upcoming;
    }
}
