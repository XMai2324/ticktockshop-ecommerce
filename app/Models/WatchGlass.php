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
        'image',
        'description',
    ];
}
