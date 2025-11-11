<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Rating;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class RatingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Strategy:
        // - For each order_item, create a rating from the order's user for that product
        // - Limit number per product to avoid too many

        $pairs = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('orders.user_id', 'order_items.product_id')
            ->distinct()
            ->get();

        foreach ($pairs as $pair) {
            // create random rating and optional comment
            $ratingValue = rand(3, 5); // prefer positive
            $comment = null;
            if (rand(0, 1)) {
                $comment = 'Sản phẩm tốt, hài lòng.';
            }

            Rating::updateOrCreate(
                ['user_id' => $pair->user_id, 'product_id' => $pair->product_id],
                ['rating' => $ratingValue, 'comment' => $comment]
            );
        }

        // Additionally, for products without any orders, create some sample ratings from random users
        $productsWithRatings = Rating::pluck('product_id')->unique()->toArray();
        $sampleProducts = Product::whereNotIn('id', $productsWithRatings)->take(10)->get();
        $users = User::inRandomOrder()->take(20)->get();

        foreach ($sampleProducts as $product) {
            foreach ($users->take(rand(1,3)) as $user) {
                Rating::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => rand(3,5),
                    'comment' => 'Dùng thử khá ổn.'
                ]);
            }
        }
    }
}
