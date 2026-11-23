<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Notification;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('ArenaDemo2026!');

        $users = [];
        foreach ([
            ['admin@arena.test', 'arena_admin', 'Администратор ARENA', 'admin'],
            ['organizer@arena.test', 'organizer', 'Организатор ARENA', 'organizer'],
            ['captain@arena.test', 'vortex_ig', 'Vortex Captain', 'captain'],
            ['player@arena.test', 'entryfrag', 'Entry Frag', 'player'],
            ['player2@arena.test', 'smoke_main', 'Smoke Main', 'captain'],
        ] as [$email, $nickname, $name, $role]) {
            $users[$email] = User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'nickname' => $nickname, 'password' => $password, 'role' => $role]
            );
        }

        $cs2 = Game::updateOrCreate(['slug' => 'counter-strike-2'], [
            'name' => 'Counter-Strike 2', 'short_name' => 'CS2', 'is_active' => true,
        ]);
        $valorant = Game::updateOrCreate(['slug' => 'valorant'], [
            'name' => 'VALORANT', 'short_name' => 'VAL', 'is_active' => true,
        ]);
        Game::updateOrCreate(['slug' => 'dota-2'], [
            'name' => 'Dota 2', 'short_name' => 'DOTA', 'is_active' => true,
        ]);

        $team = Team::updateOrCreate(['tag' => 'VRTX'], [
            'name' => 'VORTEX', 'description' => 'Команда для демонстрационного турнира.',
            'captain_id' => $users['captain@arena.test']->id,
        ]);
        $secondTeam = Team::updateOrCreate(['tag' => 'NOVA'], [
            'name' => 'NOVA FIVE', 'description' => 'Соперники VORTEX.',
            'captain_id' => $users['player2@arena.test']->id,
        ]);

        foreach ([
            [$team, $users['captain@arena.test'], 'captain'],
            [$team, $users['player@arena.test'], 'player'],
            [$secondTeam, $users['player2@arena.test'], 'captain'],
        ] as [$teamRow, $user, $role]) {
            \Illuminate\Support\Facades\DB::table('team_members')->updateOrInsert(
                ['team_id' => $teamRow->id, 'user_id' => $user->id],
                ['member_role' => $role, 'joined_at' => now()]
            );
        }

        $open = Tournament::updateOrCreate(['slug' => 'arena-open-01'], [
            'game_id' => $cs2->id,
            'organizer_id' => $users['organizer@arena.test']->id,
            'title' => 'ARENA OPEN #01',
            'description' => 'Открытый турнир по Counter-Strike 2. Собери состав и пройди путь до финала.',
            'rules' => 'Формат single elimination. Команда должна подтвердить участие до начала турнира.',
            'format' => 'single_elimination',
            'participant_type' => 'team',
            'team_size' => 5,
            'max_participants' => 16,
            'registration_starts_at' => now()->subDay(),
            'registration_ends_at' => now()->addDays(5),
            'starts_at' => now()->addDays(7),
            'status' => 'published',
        ]);
        $night = Tournament::updateOrCreate(['slug' => 'night-cup'], [
            'game_id' => $cs2->id,
            'organizer_id' => $users['organizer@arena.test']->id,
            'title' => 'NIGHT CUP',
            'description' => 'Вечерний кубок для команд, готовых проверить сыгранность и тактику.',
            'rules' => 'Double elimination. Все матчи проводятся по расписанию.',
            'format' => 'double_elimination',
            'participant_type' => 'team',
            'team_size' => 5,
            'max_participants' => 8,
            'registration_starts_at' => now()->subDay(),
            'registration_ends_at' => now()->addDays(10),
            'starts_at' => now()->addDays(14),
            'status' => 'published',
        ]);

        Registration::updateOrCreate(['tournament_id' => $open->id, 'team_id' => $team->id], [
            'user_id' => $users['captain@arena.test']->id, 'status' => 'accepted',
        ]);
        Registration::updateOrCreate(['tournament_id' => $open->id, 'team_id' => $secondTeam->id], [
            'user_id' => $users['player2@arena.test']->id, 'status' => 'accepted',
        ]);
        Registration::updateOrCreate(['tournament_id' => $night->id, 'team_id' => $secondTeam->id], [
            'user_id' => $users['player2@arena.test']->id, 'status' => 'pending',
        ]);

        TournamentMatch::updateOrCreate(['tournament_id' => $open->id, 'round_number' => 1, 'match_number' => 1], [
            'team_a_id' => $team->id,
            'team_b_id' => $secondTeam->id,
            'scheduled_at' => now()->addDays(7),
            'status' => 'scheduled',
        ]);
    }
}
