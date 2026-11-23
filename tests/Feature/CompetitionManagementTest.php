<?php

namespace Tests\\Feature;

use App\\Models\\Game;
use App\\Models\\Registration;
use App\\Models\\Team;
use App\\Models\\Tournament;
use App\\Models\\TournamentMatch;
use App\\Models\\User;
use Illuminate\\Foundation\\Testing\\RefreshDatabase;
use Tests\\TestCase;

class CompetitionManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $organizer;
    private User $captain;
    private User $player;
    private Team $teamA;
    private Team $teamB;
    private Tournament $tournament;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create(['name' => 'Admin', 'nickname' => 'admin_test', 'email' => 'admin@test.local', 'password' => bcrypt('SecretPass2026'), 'role' => 'admin']);
        $this->organizer = User::create(['name' => 'Organizer', 'nickname' => 'organizer_test', 'email' => 'organizer@test.local', 'password' => bcrypt('SecretPass2026'), 'role' => 'organizer']);
        $this->captain = User::create(['name' => 'Captain', 'nickname' => 'captain_test', 'email' => 'captain@test.local', 'password' => bcrypt('SecretPass2026'), 'role' => 'captain']);
        $this->player = User::create(['name' => 'Player', 'nickname' => 'player_test', 'email' => 'player@test.local', 'password' => bcrypt('SecretPass2026'), 'role' => 'player']);

        $game = Game::create(['name' => 'Counter-Strike 2', 'slug' => 'cs2-test', 'short_name' => 'CS2']);
        $this->teamA = Team::create(['name' => 'Alpha Team', 'tag' => 'ALPHA', 'captain_id' => $this->captain->id]);
        $captainB = User::create(['name' => 'Captain B', 'nickname' => 'captain_b', 'email' => 'captainb@test.local', 'password' => bcrypt('SecretPass2026'), 'role' => 'captain']);
        $this->teamB = Team::create(['name' => 'Bravo Team', 'tag' => 'BRAVO', 'captain_id' => $captainB->id]);

        $this->teamA->members()->attach($this->captain->id, ['member_role' => 'captain', 'joined_at' => now()]);
        $this->tournament = Tournament::create([
            'game_id' => $game->id, 'organizer_id' => $this->organizer->id,
            'title' => 'Test Arena Cup', 'slug' => 'test-arena-cup',
            'format' => 'single_elimination', 'participant_type' => 'team',
            'team_size' => 5, 'max_participants' => 8, 'status' => 'published',
        ]);
        foreach ([$this->teamA, $this->teamB] as $team) {
            Registration::create(['tournament_id' => $this->tournament->id, 'team_id' => $team->id, 'status' => 'accepted']);
        }
    }

    public function test_organizer_can_record_match_score_and_winner_is_calculated(): void
    {
        $match = TournamentMatch::create([
            'tournament_id' => $this->tournament->id,
            'round_number' => 1, 'match_number' => 1,
            'team_a_id' => $this->teamA->id, 'team_b_id' => $this->teamB->id,
            'status' => 'scheduled',
        ]);

        $this->actingAs($this->organizer)
            ->patch(route('organizer.matches.score', $match), ['score_a' => 13, 'score_b' => 9])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('matches', [
            'id' => $match->id, 'score_a' => 13, 'score_b' => 9,
            'winner_team_id' => $this->teamA->id, 'status' => 'completed',
        ]);
    }

    public function test_match_score_must_not_be_a_draw(): void
    {
        $match = TournamentMatch::create([
            'tournament_id' => $this->tournament->id,
            'team_a_id' => $this->teamA->id, 'team_b_id' => $this->teamB->id,
            'status' => 'scheduled',
        ]);

        $this->actingAs($this->organizer)
            ->from('/tournaments/test-arena-cup')
            ->patch(route('organizer.matches.score', $match), ['score_a' => 10, 'score_b' => 10])
            ->assertSessionHasErrors('score_b');

        $this->assertDatabaseHas('matches', ['id' => $match->id, 'status' => 'scheduled']);
    }

    public function test_player_cannot_change_match_score(): void
    {
        $match = TournamentMatch::create([
            'tournament_id' => $this->tournament->id,
            'team_a_id' => $this->teamA->id, 'team_b_id' => $this->teamB->id,
        ]);

        $this->actingAs($this->player)
            ->patch(route('organizer.matches.score', $match), ['score_a' => 2, 'score_b' => 1])
            ->assertForbidden();
    }

    public function test_captain_can_add_and_remove_team_members(): void
    {
        $this->actingAs($this->captain)
            ->post(route('teams.members.add', $this->teamA), ['member' => $this->player->nickname])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('team_members', ['team_id' => $this->teamA->id, 'user_id' => $this->player->id]);

        $this->actingAs($this->captain)
            ->delete(route('teams.members.remove', [$this->teamA, $this->player]))
            ->assertRedirect();

        $this->assertDatabaseMissing('team_members', ['team_id' => $this->teamA->id, 'user_id' => $this->player->id]);
    }

    public function test_admin_can_change_another_users_role_but_not_their_own(): void
    {
        $this->actingAs($this->admin)
            ->patch(route('admin.users.role', $this->player), ['role' => 'organizer'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $this->player->id, 'role' => 'organizer']);

        $this->actingAs($this->admin)
            ->from('/admin')
            ->patch(route('admin.users.role', $this->admin), ['role' => 'player'])
            ->assertSessionHasErrors('role');
    }
}
