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
        WatchStrap::truncate();
       $straps = [
            ['Dây da đen trơn', 'Da', 'Đen', 350000, 'strap1.jpg'],
            ['Dây da nâu trơn', 'Da', 'Nâu', 360000, 'strap2.jpg'],
            ['Dây da đen vân cá sấu', 'Da vân', 'Đen', 370000, 'strap3.jpg'],
            ['Dây da nâu vân cá sấu', 'Da vân', 'Nâu', 380000, 'strap4.jpg'],
            ['Dây kim loại bạc', 'Kim loại', 'Bạc', 400000, 'strap5.jpg'],
            ['Dây kim loại đen', 'Kim loại', 'Đen', 420000, 'strap6.jpg'],
            ['Dây kim loại vàng', 'Kim loại', 'Vàng', 450000, 'strap7.jpg'],
            ['Dây vải nato xanh navy', 'Vải', 'Xanh navy', 180000, 'strap8.jpg'],
            ['Dây vải nato sọc đỏ xanh', 'Vải', 'Đỏ - Xanh', 190000, 'strap9.webp'],
            ['Dây cao su thể thao đen', 'Cao su', 'Đen', 220000, 'strap10.jpg'],
            ['Dây cao su trắng', 'Cao su', 'Trắng', 230000, 'strap11.png'],
            ['Dây cao su cam', 'Cao su', 'Cam', 240000, 'strap12.jpg'],
            ['Dây lưới thép bạc', 'Thép lưới', 'Bạc', 460000, 'strap13.jpg_.avif'],
            ['Dây lưới thép vàng hồng', 'Thép lưới', 'Vàng hồng', 480000, 'strap14.jpg'],
            ['Dây da bò Ý nâu đỏ', 'Da bò', 'Nâu đỏ', 490000, 'strap15.jpg'],
            ['Dây da bò Ý đen nhám', 'Da bò', 'Đen', 500000, 'strap16.jpg'],
            ['Dây nato đen xám', 'Vải', 'Đen - Xám', 200000, 'strap17.jpg'],
            ['Dây da lộn xám', 'Da lộn', 'Xám', 510000, 'strap18.webp'],
            ['Dây da lộn nâu be', 'Da lộn', 'Nâu be', 520000, 'strap19.jpg'],
            ['Dây giả kim mắt to', 'Kim loại giả', 'Bạc', 310000, 'strap20.jpg'],
        ];

        foreach ($straps as [$name, $material, $color, $price, $image]) {
            WatchStrap::create([
                'name' => $name,
                'material' => $material,
                'color' => $color,
                'price' => $price,
                'image' => $image,
            ]);
        }
    }
}
