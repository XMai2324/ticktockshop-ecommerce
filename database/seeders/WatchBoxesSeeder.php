<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WatchBoxesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WatchBoxes::create ([
            'name'=> 'Masamu Zenbox MSM-HOP-6-V1',
            'image' => 'hop1.png',
            'description' => ' Kích thước 24 cm x 23 cm x 10 cm với 6 ngăn đựng rộng rãi thoải mái',
            'price' => 900000
        ]);

         WatchBoxes::create ([
            'name'=> 'Masamu Zenbox MSM-HOP-6-V2',
            'image' => 'hop2.png',
            'description' => ' Kích thước 8 cm x 13 cm x 10 cm với 6 ngăn đựng rộng rãi thoải mái',
            'price' => 1200000
        ]);
    }
}
