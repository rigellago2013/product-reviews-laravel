<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reviews;
use App\Models\Products;
use App\Models\User;

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all existing products
        $products = Products::all();

        // Create reviews for each product
        $products->each(function ($product) {
            // Assuming you have a relationship between Review and User
            $user = User::inRandomOrder()->first();

            // Create 5 reviews for each product with random comments and ratings
            Reviews::factory()->count(5)->create([
                'product_id' => $product->id,
                'user_name' => $user->name,
            ]);
        });
    }
}
