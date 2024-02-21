<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Products;
use App\Models\User;

class ReviewsControllerTest extends TestCase
{
    //Can create a review
    public function test_can_create_review_for_product()
    {
        $product = Products::factory()->create(); // Correct the model reference
        $user = User::factory()->create();
    
        $response = $this->postJson('/api/products/' . $product->id . '/reviews', [
            'user_name' => $user->name,
            'rating' => 4,
            'comment' => 'Great product!',
        ]);
    
        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'user_name' => $user->name,
                'rating' => 4,
                'comment' => 'Great product!',
            ]
        ]);
    }

    //Cannot create without required parameters
    public function test_cannot_create_review_without_required_fields()
    {
        $product = Products::factory()->create();

        $response = $this->postJson('/api/products/' . $product->id . '/reviews', []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['user_name', 'rating', 'comment']);
    }

    //Cannot create a review with invalikd rating
    public function test_cannot_create_review_with_invalid_rating()
    {
        $product = Products::factory()->create();

        $response = $this->postJson('/api/products/' . $product->id . '/reviews', [
            'user_name' => 'John Doe',
            'rating' => 6, // Invalid rating
            'comment' => 'Nice product!',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['rating']);
    }

    //Test cannot create review for non-existing product
    public function test_cannot_create_review_for_non_existing_product()
    {
        $user = User::factory()->create();
        $nonExistingProductId = 999;

        $data = [
            'user_name' => $user->name,
            'rating' => rand(1,5),
            'comment' => 'Nice product!',
        ];

        $response = $this->postJson("/api/products/{$nonExistingProductId}/reviews", $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Product not found']);
    }

    // Test creating a review with invalid data
    public function test_cannot_create_review_with_invalid_data()
    {
        $user = User::factory()->create();
        // Create a product
        $product = Products::factory()->create();

        $data = [
            'user_name' => $user->name,
            'rating' => 'invalid_rating', // Invalid rating data
            'comment' => 'Bad product',
        ];

        $response = $this->postJson("/api/products/{$product->id}/reviews", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['rating']);
    }
}