<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> 5d9f0794aae488d807e1ffae16aeb5cad2dd7a07
=======
>>>>>>> 6fb48dd72ac4be54a2a26ff5b43d6a47ec6ea6c8
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
