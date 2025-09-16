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
        $brand = ['Casio','Rolex', 'Citizen', 'Rado' ,'Seiko'];
        foreach ($brand as $name) {
            Brand::create(['name' => $name]);
        }
    }
}
