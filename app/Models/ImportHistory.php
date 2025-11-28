<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    protected $table = 'import_history';

    protected $fillable = [
        'product_id',
        'quantity_before', 'quantity_added', 'quantity_after',
        'cost_price_before', 'cost_price_after',
        'sell_price_after'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
