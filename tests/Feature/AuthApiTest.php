<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Nguyen Van A',
            'email' => 'citizen@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.role', 'citizen')
            ->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'email' => 'citizen@example.com',
            'role' => 'citizen',
        ]);
    }

    public function test_user_can_login_and_logout(): void
    {
        User::factory()->create([
            'email' => 'citizen@example.com',
            'password' => 'password123',
        ]);

        $token = $this->postJson('/api/auth/login', [
            'email' => 'citizen@example.com',
            'password' => 'password123',
        ])->assertOk()->json('token');

        $this->withToken($token)
            ->postJson('/api/auth/logout')
            ->assertOk()
            ->assertJsonPath('message', 'Logged out.');
    }

    public function test_me_requires_authenticated_user(): void
    {
        $this->getJson('/api/auth/me')->assertUnauthorized();

        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/auth/me')->assertOk();
    }
}
