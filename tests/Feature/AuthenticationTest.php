<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_can_register_and_is_signed_in(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test Player',
            'nickname' => 'test_player',
            'email' => 'test@example.test',
            'password' => 'SecretPass2026',
            'password_confirmation' => 'SecretPass2026',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'test@example.test', 'role' => 'player']);
    }

    public function test_user_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create(['password' => bcrypt('SecretPass2026')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'SecretPass2026'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}
