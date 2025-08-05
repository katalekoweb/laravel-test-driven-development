<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_home_page_contains_doucmnetation(): void
    {
        $response = $this->get('/');

        # asert see
        $response->assertSee("Documentation");
        $response->assertStatus(200);
    }
}
