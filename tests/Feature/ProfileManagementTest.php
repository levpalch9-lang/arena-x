<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile_identity(): void
    {
        $user = User::create([
            'name' => 'Old Name',
            'email' => 'old@example.test',
            'nickname' => 'old_nick',
            'password' => Hash::make('Password123!'),
            'role' => 'player',
        ]);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'New Name',
                'email' => 'new@example.test',
                'nickname' => 'new_nick',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.test',
            'nickname' => 'new_nick',
        ]);
    }

    public function test_user_must_confirm_current_password_to_change_password(): void
    {
        $user = User::create([
            'name' => 'Player',
            'email' => 'player@example.test',
            'nickname' => 'player_one',
            'password' => Hash::make('Password123!'),
            'role' => 'player',
        ]);

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Player',
                'email' => 'player@example.test',
                'nickname' => 'player_one',
                'password' => 'NewPassword123!',
                'password_confirmation' => 'NewPassword123!',
                'current_password' => 'WrongPassword123!',
            ])
            ->assertSessionHasErrors('current_password');
    }

    public function test_profile_settings_require_authentication(): void
    {
        $this->get('/profile')->assertRedirect('/login');
    }
}
