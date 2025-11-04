<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class UpdateCategorySlugsSeeder extends Seeder
{
    public function run(): void
    {
        // Nếu bảng đã có dữ liệu, update slug theo name
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->slug = Str::slug($category->name); // ví dụ: "Cặp đôi" -> "cap-doi"
            $category->save();
        }

        // Nếu bảng chưa có 3 category chính thì tạo mới
        $defaultCategories = [
            'Nữ'      => 'nu',
            'Nam'     => 'nam',
            'Cặp đôi' => 'cap-doi',
        ];

        foreach ($defaultCategories as $name => $slug) {
            Category::updateOrCreate(
                ['name' => $name],
                ['slug' => $slug]
            );
        }
    }
}
