<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
    }

    public function test_products_page_contains_empty_table(): void
    {
        $response = $this->actingAs($this->user)->get('/products');
        
        $response->assertStatus(200);
        $response->assertSee(__('No products found'));
    }

    public function test_products_page_contains_nonempty_table(): void
    {
        $product = Product::query()->create([
            'name' => 'Test Product',
            'price' => 190.99,
            'description' => 'A product for testing',
            'available' => true,
        ]);

        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(200);
        $response->assertDontSee(__('No products found'));
        # False positive
        $response->assertSee('Test Product');

        # we need to test the data
        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
    }

    public function test_paginated_products_table_doesnt_contains_11th_record () {
        // arrange
        $products = Product::factory(11)->create();
        $lastProduct = $products->last();

        // act
        $response = $this->actingAs($this->user)->get('/products');

        // assert
        $response->assertStatus(200);
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    private function createUser(): User {
        $user = User::factory()->create();
        return $user;
    }

}
