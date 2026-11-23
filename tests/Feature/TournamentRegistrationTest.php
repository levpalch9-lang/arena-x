<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TournamentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    private User $captain;
    private User $organizer;
    private Team $team;
    private Tournament $tournament;

    protected function setUp(): void
    {
        parent::setUp();

        $this->captain = User::create([
            'name' => 'Captain', 'nickname' => 'captain_one',
            'email' => 'captain@example.test', 'password' => bcrypt('SecretPass2026'), 'role' => 'captain',
        ]);
        $this->organizer = User::create([
            'name' => 'Organizer', 'nickname' => 'organizer_one',
            'email' => 'organizer@example.test', 'password' => bcrypt('SecretPass2026'), 'role' => 'organizer',
        ]);
        $game = Game::create(['name' => 'Counter-Strike 2', 'slug' => 'cs2', 'short_name' => 'CS2']);
        $this->team = Team::create(['name' => 'Test Team', 'tag' => 'TEST', 'captain_id' => $this->captain->id]);
        $this->tournament = Tournament::create([
            'game_id' => $game->id, 'organizer_id' => $this->organizer->id,
            'title' => 'Test Cup', 'slug' => 'test-cup', 'format' => 'single_elimination',
            'participant_type' => 'team', 'team_size' => 5, 'max_participants' => 8,
            'registration_starts_at' => now()->subDay(), 'registration_ends_at' => now()->addDay(),
            'starts_at' => now()->addDays(3), 'status' => 'published',
        ]);
    }

    public function test_captain_can_submit_team_application_once(): void
    {
        $payload = ['team_id' => $this->team->id];

        $this->actingAs($this->captain)
            ->post('/tournaments/test-cup/register', $payload)
            ->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'tournament_id' => $this->tournament->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->captain)
            ->from('/tournaments/test-cup')
            ->post('/tournaments/test-cup/register', $payload)
            ->assertSessionHasErrors('registration');

        $this->assertSame(1, Registration::count());
    }

    public function test_organizer_can_accept_application(): void
    {
        $registration = Registration::create([
            'tournament_id' => $this->tournament->id,
            'user_id' => $this->captain->id,
            'team_id' => $this->team->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->organizer)
            ->patch('/organizer/registrations/'.$registration->id, ['status' => 'accepted'])
            ->assertRedirect();

        $this->assertDatabaseHas('registrations', ['id' => $registration->id, 'status' => 'accepted']);
        $this->assertDatabaseHas('notifications', ['user_id' => $this->captain->id, 'title' => 'Заявка одобрена']);
    }
}
