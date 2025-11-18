<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchStrap extends Model
{
    protected $fillable = ['name', 'material', 'color','image', 'price', 'is_hidden'];
    protected $table = '2025_07_16_092806_watch_straps';
    protected $casts = [
        'is_hidden' => 'boolean',
    ];
}
