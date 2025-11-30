<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchBox extends Model
{

    protected $table = 'watch_boxes';
    protected $fillable = [
        'name',
        'image',
        'description',
        'price',
        'is_hidden',
    ];
    protected $casts = [
        'is_hidden' => 'boolean',
    ];
}
