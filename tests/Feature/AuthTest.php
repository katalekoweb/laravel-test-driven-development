<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase {

    use RefreshDatabase;

    public function test_login_redirects_to_products () {

        // arrange
        $user = User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => bcrypt('password')
        ]);

        // act
        $response = $this->post('/login', [
            'email' => 'user@user.com',
            'password' => 'password'
        ]);

        // assert
        $response->assertStatus(302);
        $response->assertRedirect('/products');

    }

    public function test_unauthenticated_user_cannot_access_product () {
        // act 
        $response = $this->get("/products");
    
        // assert
        $response->assertStatus(302);
        $response->assertRedirect("/login");
    }
}