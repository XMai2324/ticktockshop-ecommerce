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
<<<<<<< HEAD
        $brands = ['Casio','Rolex', 'Citizen', 'Rado' ,'Seiko'];
        foreach ($brands as $name) {
=======

        $brands = ['Casio','Rolex', 'Citizen', 'Rado' ,'Seiko'];

        foreach ($brands as $name) {

>>>>>>> 5d9f0794aae488d807e1ffae16aeb5cad2dd7a07
            Brand::create(['name' => $name]);
        }
    }
}
