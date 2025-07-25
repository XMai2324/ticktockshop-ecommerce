<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WatchStrap;

class WatchStrapsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WatchStrap::create([
            'name' => 'Dây A' ,
            'material' => 'Nhựa',
            'color' => 'Đen',
            'price' => '350000',
            'product_id' => 1,

        ]);
    }
}
