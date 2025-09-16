<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

<<<<<<< HEAD

=======
>>>>>>> 5d9f0794aae488d807e1ffae16aeb5cad2dd7a07
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
