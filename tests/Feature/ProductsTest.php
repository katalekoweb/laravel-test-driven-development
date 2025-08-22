<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    protected function setUp (): void {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_home_page_contains_empty_table(): void
    {
        // arrange
        // $user = $this->createUser();

        // act
        $response = $this->actingAs($this->user)->get('/products');

        $response->assertSee(__('No records found'));
        $response->assertStatus(200);
    }

    public function test_home_page_contains_non_empty_table(): void
    {
        // arrange
        // $user = $this->createUser();
        $product = Product::query()->create([
            "name" => "Product 1",
            "price" => 100
        ]);

        // act
        $response = $this->actingAs($this->user)->get('/products');

        // assert
        $response->assertViewHas('products', function ($collection) use ($product) {
            return $collection->contains($product);
        });
        $response->assertDontSee(__('No records found'));
        $response->assertStatus(200);
    }

    public function test_paginated_products_table_doesnt_contains_11th_records()
    {
        // arrange
        // $user = $this->createUser();
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

    private function createUser (): User {
        return User::factory()->create();
    }
}
