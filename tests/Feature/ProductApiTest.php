<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); 
    }

    public function test_can_list_products()
    {
        $this->authenticate();

        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_can_show_single_product()
    {
        $this->authenticate();

        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/product/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $product->id]);
    }

    public function test_can_create_product()
    {
        $this->authenticate();

        $data = [
            'name' => 'Test Product',
            'price' => 99.99,
        ];

        $response = $this->postJson('/api/v1/products', $data);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Test Product']);

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_can_update_product()
    {
        $this->authenticate();

        $product = Product::factory()->create();

        $updateData = ['name' => 'Updated Product'];

        $response = $this->putJson("/api/v1/product/{$product->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Product']);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Product']);
    }

    public function test_can_delete_product()
    {
        $this->authenticate();

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/product/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
