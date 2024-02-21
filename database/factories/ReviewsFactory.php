<?php

namespace Database\Factories;

use App\Models\Products;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Reviews::class;

    public function definition()
    {
        // Assuming you have a relationship between Review and User
        $user = User::factory()->create();
        $product = Products::factory()->create();
        return [
            'product_id' => $product->id,
            'user_name' => $user->name,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph,
        ];
    }
}