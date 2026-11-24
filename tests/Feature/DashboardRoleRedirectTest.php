<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardRoleRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_redirected_to_the_control_center(): void
    {
        $admin = User::create([
            'name' => 'Arena Admin',
            'email' => 'admin-redirect@example.test',
            'nickname' => 'arena_admin_test',
            'password' => Hash::make('Password123!'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/dashboard')
            ->assertRedirect('/admin');
    }

    public function test_tournament_catalog_search_filters_database_results(): void
    {
        $this->seed();

        $this->get('/tournaments?q=NIGHT')
            ->assertOk()
            ->assertSee('NIGHT CUP')
            ->assertDontSee('ARENA OPEN #01');
    }
}
