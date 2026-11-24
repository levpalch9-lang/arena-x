<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            ['captain2@arena.test', 'frostbyte', 'FrostByte Captain', 'captain'],
            ['captain3@arena.test', 'redline', 'Redline Captain', 'captain'],
        ] as [$email, $nickname, $name, $role]) {
            $users[$email] = User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'nickname' => $nickname, 'password' => $password, 'role' => $role]
            );
        }

        $cs2 = Game::updateOrCreate(['slug' => 'counter-strike-2'], [
            'name' => 'Counter-Strike 2', 'short_name' => 'CS2', 'is_active' => true,
        ]);
        Game::updateOrCreate(['slug' => 'valorant'], [
            'name' => 'VALORANT', 'short_name' => 'VAL', 'is_active' => true,
        ]);
        Game::updateOrCreate(['slug' => 'dota-2'], [
            'name' => 'Dota 2', 'short_name' => 'DOTA', 'is_active' => true,
        ]);

        $team = Team::updateOrCreate(['tag' => 'VRTX'], [
            'name' => 'VORTEX', 'description' => 'Команда с упором на контроль карты и дисциплину.',
            'captain_id' => $users['captain@arena.test']->id,
        ]);
        $secondTeam = Team::updateOrCreate(['tag' => 'NOVA'], [
            'name' => 'NOVA FIVE', 'description' => 'Сбалансированный состав для командных серий.',
            'captain_id' => $users['player2@arena.test']->id,
        ]);
        $thirdTeam = Team::updateOrCreate(['tag' => 'FRST'], [
            'name' => 'FROSTBYTE', 'description' => 'Тактический состав с акцентом на контроль карты.',
            'captain_id' => $users['captain2@arena.test']->id,
        ]);
        $fourthTeam = Team::updateOrCreate(['tag' => 'RDLN'], [
            'name' => 'REDLINE', 'description' => 'Агрессивный состав для быстрых атак и ретейков.',
            'captain_id' => $users['captain3@arena.test']->id,
        ]);

        foreach ([
            [$team, $users['captain@arena.test'], 'captain'],
            [$team, $users['player@arena.test'], 'player'],
            [$secondTeam, $users['player2@arena.test'], 'captain'],
            [$thirdTeam, $users['captain2@arena.test'], 'captain'],
            [$thirdTeam, $users['player@arena.test'], 'player'],
            [$fourthTeam, $users['captain3@arena.test'], 'captain'],
            [$fourthTeam, $users['player2@arena.test'], 'player'],
        ] as [$teamRow, $user, $role]) {
            DB::table('team_members')->updateOrInsert(
                ['team_id' => $teamRow->id, 'user_id' => $user->id],
                ['member_role' => $role, 'joined_at' => now()]
            );
        }

        $open = Tournament::updateOrCreate(['slug' => 'arena-open-01-cs2'], [
            'game_id' => $cs2->id,
            'organizer_id' => $users['organizer@arena.test']->id,
            'title' => 'ARENA OPEN #01',
            'description' => 'Открытый турнир по Counter-Strike 2. Собери состав и пройди путь до финала.',
            'rules' => 'Формат single elimination. Команда должна подтвердить участие до начала турнира.',
            'format' => 'single_elimination',
            'participant_type' => 'team',
            'team_size' => 5,
            'max_participants' => 16,
            'registration_starts_at' => now()->subDays(12),
            'registration_ends_at' => now()->subDays(8),
            'starts_at' => now()->subDays(7),
            'status' => 'completed',
        ]);
        $night = Tournament::updateOrCreate(['slug' => 'night-cup-cs2'], [
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
        $invitational = Tournament::updateOrCreate(['slug' => 'arena-x-invitational'], [
            'game_id' => $cs2->id,
            'organizer_id' => $users['organizer@arena.test']->id,
            'title' => 'ARENA X INVITATIONAL',
            'description' => 'Пригласительный турнир четырёх составов. Полуфиналы и финальная серия за титул.',
            'rules' => 'Single elimination. Матчи до 13 раундов, финал BO3. Запрещены читы и оскорбления.',
            'format' => 'single_elimination',
            'participant_type' => 'team',
            'team_size' => 5,
            'max_participants' => 8,
            'prize_pool' => '20 000 ₽',
            'registration_starts_at' => now()->subDays(5),
            'registration_ends_at' => now()->subDay(),
            'starts_at' => now()->subHours(4),
            'status' => 'ongoing',
        ]);
        $winter = Tournament::updateOrCreate(['slug' => 'winter-frag-cup'], [
            'game_id' => $cs2->id,
            'organizer_id' => $users['organizer@arena.test']->id,
            'title' => 'WINTER FRAG CUP',
            'description' => 'Открытый зимний кубок ARENA X. Регистрация команд уже доступна.',
            'rules' => 'Single elimination. Состав — до пяти основных игроков и одного запасного. Матчи по расписанию.',
            'format' => 'single_elimination',
            'participant_type' => 'team',
            'team_size' => 5,
            'max_participants' => 16,
            'prize_pool' => '15 000 ₽',
            'registration_starts_at' => now()->subDay(),
            'registration_ends_at' => now()->addDays(6),
            'starts_at' => now()->addDays(8),
            'status' => 'published',
        ]);
        $campus = Tournament::updateOrCreate(['slug' => 'campus-clash-01'], [
            'game_id' => $cs2->id,
            'organizer_id' => $users['organizer@arena.test']->id,
            'title' => 'CAMPUS CLASH #01',
            'description' => 'Завершённый кампусный турнир. Архив сетки, матчей и итоговых результатов.',
            'rules' => 'Single elimination. Итоговый результат подтверждён организатором.',
            'format' => 'single_elimination',
            'participant_type' => 'team',
            'team_size' => 5,
            'max_participants' => 8,
            'prize_pool' => '10 000 ₽',
            'registration_starts_at' => now()->subDays(18),
            'registration_ends_at' => now()->subDays(11),
            'starts_at' => now()->subDays(10),
            'status' => 'completed',
        ]);

        foreach ([
            [$open, $team, $users['captain@arena.test'], 'accepted'],
            [$open, $secondTeam, $users['player2@arena.test'], 'accepted'],
            [$night, $secondTeam, $users['player2@arena.test'], 'pending'],
            [$invitational, $team, $users['captain@arena.test'], 'accepted'],
            [$invitational, $secondTeam, $users['player2@arena.test'], 'accepted'],
            [$invitational, $thirdTeam, $users['captain2@arena.test'], 'accepted'],
            [$invitational, $fourthTeam, $users['captain3@arena.test'], 'accepted'],
            [$winter, $thirdTeam, $users['captain2@arena.test'], 'pending'],
            [$winter, $fourthTeam, $users['captain3@arena.test'], 'pending'],
            [$campus, $team, $users['captain@arena.test'], 'accepted'],
            [$campus, $secondTeam, $users['player2@arena.test'], 'accepted'],
            [$campus, $thirdTeam, $users['captain2@arena.test'], 'accepted'],
            [$campus, $fourthTeam, $users['captain3@arena.test'], 'accepted'],
        ] as [$tournament, $teamRow, $user, $status]) {
            Registration::updateOrCreate(
                ['tournament_id' => $tournament->id, 'team_id' => $teamRow->id],
                ['user_id' => $user->id, 'status' => $status]
            );
        }

        $matches = [
            [$open, 1, 1, $team, $secondTeam, now()->subDays(7), 13, 9, $team, 'completed', 'Mirage'],
            [$invitational, 1, 1, $team, $secondTeam, now()->subHours(3), 13, 8, $team, 'completed', 'Mirage'],
            [$invitational, 1, 2, $thirdTeam, $fourthTeam, now()->subHours(2), 7, 13, $fourthTeam, 'completed', 'Ancient'],
            [$invitational, 2, 1, $team, $fourthTeam, now()->addHours(2), null, null, null, 'scheduled', 'Inferno'],
            [$campus, 1, 1, $team, $secondTeam, now()->subDays(10), 16, 12, $team, 'completed', 'Nuke'],
            [$campus, 1, 2, $thirdTeam, $fourthTeam, now()->subDays(10), 13, 11, $thirdTeam, 'completed', 'Anubis'],
            [$campus, 2, 1, $team, $thirdTeam, now()->subDays(9), 2, 0, $team, 'completed', 'Mirage'],
        ];

        foreach ($matches as [$tournament, $round, $number, $teamA, $teamB, $scheduledAt, $scoreA, $scoreB, $winner, $status, $map]) {
            TournamentMatch::updateOrCreate(
                ['tournament_id' => $tournament->id, 'round_number' => $round, 'match_number' => $number],
                [
                    'team_a_id' => $teamA->id,
                    'team_b_id' => $teamB->id,
                    'scheduled_at' => $scheduledAt,
                    'score_a' => $scoreA,
                    'score_b' => $scoreB,
                    'winner_team_id' => $winner?->id,
                    'status' => $status,
                    'map_name' => $map,
                ]
            );
        }
    }
}
