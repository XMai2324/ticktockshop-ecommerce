<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchGlass extends Model
{
    protected $table = 'watch_glasses';
    protected $fillable = [
        'name',
        'material',
        'color',
        'price',
        'quantity',
        'image',
        'description',
        'is_hidden',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];
}
