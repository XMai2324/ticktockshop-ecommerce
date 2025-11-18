<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchStrap extends Model
{
    protected $table = 'watch_straps';
    protected $fillable = ['name', 'material', 'color','image', 'price', 'is_hidden'];
    protected $casts = [
        'is_hidden' => 'boolean',
    ];
}
