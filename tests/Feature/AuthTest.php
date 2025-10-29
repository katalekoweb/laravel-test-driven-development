<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_unauthenticated_user_cannot_access_product(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function test_login_redirects_to_products()
    {
        $user = User::factory(1)->create(['email' => 'user@user.com'])->first();

        $response = $this->post('/login', [
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('products');
    }
}
