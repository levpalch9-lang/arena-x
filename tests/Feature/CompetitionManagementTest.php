<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    public function test_tournament_page_displays_team_standings_from_completed_matches(): void
    {
        TournamentMatch::create([
            'tournament_id' => $this->tournament->id,
            'round_number' => 1,
            'match_number' => 1,
            'team_a_id' => $this->teamA->id,
            'team_b_id' => $this->teamB->id,
            'score_a' => 13,
            'score_b' => 9,
            'winner_team_id' => $this->teamA->id,
            'status' => 'completed',
        ]);

        $this->get(route('tournaments.show', $this->tournament))
            ->assertOk()
            ->assertSee('Турнирная таблица')
            ->assertSee('Alpha Team')
            ->assertSee('Bravo Team');
    }

    public function test_match_winner_advances_to_the_correct_slot_in_the_next_round(): void
    {
        $match = TournamentMatch::create(['tournament_id' => $this->tournament->id, 'round_number' => 1, 'match_number' => 1, 'team_a_id' => $this->teamA->id, 'team_b_id' => $this->teamB->id]);
        $this->actingAs($this->organizer)->patch(route('organizer.matches.score', $match), ['score_a' => 13, 'score_b' => 7])->assertRedirect();
        $this->assertDatabaseHas('matches', ['tournament_id' => $this->tournament->id, 'round_number' => 2, 'match_number' => 1, 'team_a_id' => $this->teamA->id, 'team_b_id' => null]);
    }

    public function test_even_match_winner_advances_to_second_slot(): void
    {
        $match = TournamentMatch::create(['tournament_id' => $this->tournament->id, 'round_number' => 1, 'match_number' => 2, 'team_a_id' => $this->teamA->id, 'team_b_id' => $this->teamB->id]);
        $this->actingAs($this->organizer)->patch(route('organizer.matches.score', $match), ['score_a' => 8, 'score_b' => 13])->assertRedirect();
        $this->assertDatabaseHas('matches', ['tournament_id' => $this->tournament->id, 'round_number' => 2, 'match_number' => 1, 'team_a_id' => null, 'team_b_id' => $this->teamB->id]);
    }

    public function test_organizer_can_schedule_match_between_accepted_teams(): void
    {
        $this->actingAs($this->organizer)
            ->post(route('organizer.matches.store', $this->tournament), [
                'team_a_id' => $this->teamA->id,
                'team_b_id' => $this->teamB->id,
                'round_number' => 1,
                'match_number' => 2,
                'map_name' => 'Mirage',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('matches', [
            'tournament_id' => $this->tournament->id,
            'team_a_id' => $this->teamA->id,
            'team_b_id' => $this->teamB->id,
            'match_number' => 2,
            'map_name' => 'Mirage',
            'status' => 'scheduled',
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

    public function test_admin_can_review_pending_applications_from_any_tournament(): void
    {
        $application = Registration::create([
            'tournament_id' => $this->tournament->id,
            'user_id' => $this->player->id,
            'status' => 'pending',
            'note' => 'Готовы участвовать',
        ]);

        $this->actingAs($this->admin)
            ->patch(route('organizer.registrations.review', $application), ['status' => 'accepted'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'id' => $application->id,
            'status' => 'accepted',
            'reviewed_by' => $this->admin->id,
        ]);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->player->id,
            'title' => 'Заявка одобрена',
        ]);
    }

    public function test_organizer_cannot_review_another_organizers_application(): void
    {
        $otherOrganizer = User::create([
            'name' => 'Other Organizer',
            'nickname' => 'other_organizer',
            'email' => 'otherorganizer@test.local',
            'password' => bcrypt('SecretPass2026'),
            'role' => 'organizer',
        ]);
        $otherTournament = Tournament::create([
            'game_id' => $this->tournament->game_id,
            'organizer_id' => $otherOrganizer->id,
            'title' => 'Other Cup',
            'slug' => 'other-cup',
            'format' => 'single_elimination',
            'participant_type' => 'team',
            'team_size' => 5,
            'max_participants' => 8,
            'status' => 'published',
        ]);
        $application = Registration::create([
            'tournament_id' => $otherTournament->id,
            'team_id' => $this->teamA->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->organizer)
            ->patch(route('organizer.registrations.review', $application), ['status' => 'accepted'])
            ->assertForbidden();

        $this->assertDatabaseHas('registrations', ['id' => $application->id, 'status' => 'pending']);
    }

    public function test_admin_panel_shows_pending_applications_for_moderation(): void
    {
        Registration::create([
            'tournament_id' => $this->tournament->id,
            'user_id' => $this->player->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.index'))
            ->assertOk()
            ->assertSee('Заявки на модерации')
            ->assertSee('Принять')
            ->assertSee('Отклонить');
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
            ->assertStatus(422);
    }
}
