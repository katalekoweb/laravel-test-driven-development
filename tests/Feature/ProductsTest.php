<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_contains_empty_table(): void
    {
        $response = $this->get('/products');
        $response->assertSee(__('No records found'));
        $response->assertStatus(200);
    }

    public function test_home_page_contains_non_empty_table(): void
    {
        $product = Product::query()->create([
            "name" => "Product 1",
            "price" => 100
        ]);

        $response = $this->get('/products');
        $response->assertViewHas('products', function (Collection $collection) use ($product) {
            return $collection->contains($product);
        });
        $response->assertDontSee(__('No records found'));
        $response->assertStatus(200);
    }

}
