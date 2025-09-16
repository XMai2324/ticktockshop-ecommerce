<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call ([
            UsersSeeder::class,
            BrandsSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
            WatchStrapsSeeder::class,
            WatchBoxesSeeder::class,
            WatchGlassesSeeder::class,
            OrdersSeeder::class,
            OrderItemsSeeder::class,
            WarrantiesSeeder::class,
        ]);
    }
}
