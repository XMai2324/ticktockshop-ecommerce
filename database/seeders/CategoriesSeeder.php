<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;



class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = ['Nam', 'Nữ', 'Cặp đôi'];

        foreach ($category as $name) {
            Category::create(['name' => $name]);
            }
    }
}
