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
    private User $user, $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->admin = $this->createUser(true);
    }

    public function test_products_page_contains_empty_table(): void
    {
        $response = $this->actingAs($this->admin)->get('/products');
        
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

        $response = $this->actingAs($this->admin)->get('/products');
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
        $response = $this->actingAs($this->admin)->get('/products');

        // assert
        $response->assertStatus(200);
        $response->assertViewHas('products', function ($collection) use ($lastProduct) {
            return !$collection->contains($lastProduct);
        });
    }

    public function test_admin_can_see_products_create_button () {
        $response = $this->actingAs($this->admin)->get("/products");
        $response->assertStatus(200);
        $response->assertSee("Add new product");
    }

    // public function test_non_admin_cannot_see_products_create_button () {
    //     $response = $this->actingAs($this->user)->get("/products");
    //     $response->assertStatus(403);
    //     $response->assertDontSee("Add new product");
    // }

    // create function
    public function test_create_product_successful () {
        $product = [
            'name' => 'Test product',
            'price' => 123.90
        ];

        $response = $this->actingAs($this->admin)->post("/products", $product);

        $response->assertStatus(302);
        $response->assertRedirect("products");

        $this->assertDatabaseHas("products", $product);

        $lastProduct = Product::latest()->first();
        $this->assertEquals($product['name'], $lastProduct->name);
        $this->assertEquals($product['price'], $lastProduct->price);
    }

    // edit product test
    public function test_product_edit_contains_correct_value () {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->get("products/" . $product->id . "/edit");
        $response->assertStatus(200);
        $response->assertSee('value="' . $product->name . '"', false);
        $response->assertSee('value="' . $product->price . '"', false);
        $response->assertViewHas('product', $product);
    }

    // test update product
    public function test_product_validation_error_redirects_back_to_form () {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->put("products/" . $product->id, [
            'name' => '',
            'price' => 123
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
        $response->assertInvalid(['name']);
    }

    // test delete product
    public function test_admin_can_delete_product_successfull () {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->delete("products/" . $product->id);
        $response->assertRedirect("products");
        $response->assertStatus(302);
        $this->assertDatabaseMissing('products', $product->toArray());
        $this->assertCount(0, Product::get());
    }

    public function test_api_returns_products_list_successful () {
        // arrange
        $product = Product::factory()->create();

        // act
        $response = $this->getJson("/api/products");

        // assert
        $response->assertStatus(200);
        $response->assertJson([$product->toArray()]);
    }

    public function test_api_store_products_successful () {
        // arrange
        $product = [
            'name' => 'API Product',
            'price' => 199.99
        ];

        // act
        $response = $this->postJson("/api/products", $product);

        // assert
        $response->assertStatus(201);
        $response->assertJson($product);
    }

    public function test_api_store_products_with_form_invalid_returns_error () {
        // arrange
        $product = [
            'name' => '',
            'price' => 199.99
        ];

        // act
        $response = $this->postJson("/api/products", $product);

        // assert
        $response->assertStatus(422);
    }

    // private functions

    private function createUser(bool $is_admin = false): User {
        $user = User::factory()->create(['is_admin' => $is_admin]);
        return $user;
    }

}
