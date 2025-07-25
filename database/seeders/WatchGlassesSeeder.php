<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WatchGlass;

class WatchGlassesSeeder extends Seeder
{
    public function run(): void
    {
        WatchGlass::truncate();

        $glasses = [
            ['Kính sapphire cao cấp', 'Sapphire', 'Trong suốt', 600000, 'glass1.jpg'],
            ['Kính khoáng chống trầy', 'Khoáng', 'Trong', 300000, 'glass2.webp'],
        ];

        foreach ($glasses as [$name, $material, $color, $price, $image]) {
            WatchGlass::create([
                'name' => $name,
                'material' => $material,
                'color' => $color,
                'price' => $price,
                'image' => $image,
                'description' => 'Phụ kiện kính cường lực bảo vệ mặt đồng hồ tốt.',
            ]);
        }
    }
}
