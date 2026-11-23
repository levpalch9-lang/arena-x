<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\View\View;

class TournamentController extends Controller
{
    public function index(): View
    {
        if (config('database.connections.pgsql.host')) {
            $tournaments = Tournament::query()
                ->with('game')
                ->whereIn('status', ['published', 'registration_closed', 'ongoing', 'completed'])
                ->orderByRaw("CASE WHEN status = 'published' THEN 0 WHEN status = 'ongoing' THEN 1 ELSE 2 END")
                ->orderBy('starts_at')
                ->get();

            return view('tournaments.index', [
                'tournaments' => $tournaments,
                'demoMode' => false,
            ]);
        }

        $tournaments = collect([
            (object) [
                'title' => 'ARENA OPEN #01',
                'slug' => 'arena-open-01',
                'description' => 'Открытый турнир по Counter-Strike 2. Собери состав и пройди путь до финала.',
                'format' => 'single_elimination',
                'participant_type' => 'team',
                'max_participants' => 16,
                'status' => 'published',
                'starts_at' => now()->addDays(7),
                'game' => (object) ['name' => 'Counter-Strike 2', 'short_name' => 'CS2'],
            ],
            (object) [
                'title' => 'NIGHT CUP',
                'slug' => 'night-cup',
                'description' => 'Вечерний кубок для команд, готовых проверить сыгранность и тактику.',
                'format' => 'double_elimination',
                'participant_type' => 'team',
                'max_participants' => 8,
                'status' => 'published',
                'starts_at' => now()->addDays(14),
                'game' => (object) ['name' => 'Counter-Strike 2', 'short_name' => 'CS2'],
            ],
        ]);

        return view('tournaments.index', [
            'tournaments' => $tournaments,
            'demoMode' => true,
        ]);
    }
}
