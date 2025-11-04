<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = ['Casio','Rolex', 'Citizen', 'Rado' ,'Seiko'];
        foreach ($brands as $name) {
            Brand::create(['name' => $name]);
        }
    }
}
