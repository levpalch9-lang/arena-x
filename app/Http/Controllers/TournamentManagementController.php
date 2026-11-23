<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TournamentManagementController extends Controller
{
    public function create(): View
    {
        return view('organizer.tournaments.create', ['games' => Game::where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isOrganizer(), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:160'],
            'game_id' => ['required', 'integer', Rule::exists('games', 'id')],
            'description' => ['nullable', 'string', 'max:3000'],
            'rules' => ['nullable', 'string', 'max:5000'],
            'format' => ['required', Rule::in(['single_elimination', 'double_elimination', 'round_robin', 'swiss'])],
            'participant_type' => ['required', Rule::in(['team', 'individual'])],
            'team_size' => ['required', 'integer', 'min:1', 'max:10'],
            'max_participants' => ['required', 'integer', 'min:2', 'max:256'],
            'registration_starts_at' => ['nullable', 'date'],
            'registration_ends_at' => ['nullable', 'date', 'after_or_equal:registration_starts_at'],
            'starts_at' => ['nullable', 'date', 'after_or_equal:registration_ends_at'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);

        $slug = Str::slug($data['title']);
        if (Tournament::where('slug', $slug)->exists()) {
            $slug .= '-'.Str::lower(Str::random(5));
        }

        Tournament::create($data + [
            'slug' => $slug,
            'organizer_id' => $request->user()->id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Турнир создан.');
    }
}
