<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    public function test_example(): void
    {
        $response = $this->get('/products');
        $response->assertSee(__('No records found'));
        $response->assertStatus(200);
    }
}
