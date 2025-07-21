<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(){
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    }

    public function test_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'token',
                     'user',
                 ]);
    }

    public function test_cannot_login_with_wrong_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Invalid credentials',
                 ]);
    }

    public function test_can_login_requires_email_and_password()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(401)
                 ->assertJson([
                    'message' => 'Invalid credentials',
                ]);
    }

    public function test_can_get_user_profile(){
        $this->authenticate();

        $user = User::factory()->create();

        $response = $this->getJson('api/v1/users');

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $user->id]);
    }
}
