<?php

namespace Tests\Feature;

use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductsControllerTest extends TestCase
{
    use WithFaker;

    //Test get all Product
    public function test_can_get_all_products()
    {
        // Assuming you have a ProductsController with an index method
        $response = $this->get('/api/products');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data' => [['id', 'name', 'description', 'price', 'reviews']]]);
    }

    // Test getting product details
    public function test_can_get_product_by_id()
    {
        $product = Products::factory()->create();

        $response = $this->get('/api/products/' . $product->id);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data' => ['id', 'name', 'description', 'price', 'reviews']]);
    }

    //Test creating a Product
    public function test_can_create_product()
    {
        $data = [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['data' => ['id', 'name', 'description', 'price', 'reviews']]);
    }

    //Test updating a Product
    public function test_can_update_product()
    {
        $product = Products::factory()->create();
        $updatedData = [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data' => ['id', 'name', 'description', 'price', 'reviews']]);
    }
    
    //Test deleting a Product
    public function test_can_delete_product()
    {
        $product = Products::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Product deleted successfully']);
    }

     // Test validation when creating a Product with missing required fields
    public function test_cannot_create_product_with_missing_fields()
    {
        $data = [];

        $response = $this->postJson('/api/products', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'description', 'price']);
    }

    // Test validation when updating a Product with invalid data
    public function test_cannot_update_product_with_invalid_data()
    {
        $product = Products::factory()->create();
        $updatedData = [
            'price' => 'invalid_price',
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updatedData);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['price']);
    }

    // Test getting a non-existing product by ID
    public function test_getting_non_existing_product_by_id()
    {
        $nonExistingProductId = 999;

        $response = $this->get("/api/products/{$nonExistingProductId}");

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Product not found']);
    }

    // Test updating a non-existing Product
    public function test_cannot_update_non_existing_product()
    {
        $nonExistingProductId = 999;
        $updatedData = [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 1, 100),
        ];

        $response = $this->putJson("/api/products/{$nonExistingProductId}", $updatedData);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Product not found']);
    }

    // Test deleting a non-existing Product
    public function test_cannot_delete_non_existing_product()
    {
        $nonExistingProductId = 999;

        $response = $this->deleteJson("/api/products/{$nonExistingProductId}");

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Product not found']);
    }
}
