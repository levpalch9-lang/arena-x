<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        $teams = $request->user()->teams()->with('captain')->get();
        $captainedTeams = $request->user()->captainedTeams()->with('members')->get();

        return view('teams.index', compact('teams', 'captainedTeams'));
    }

    public function create(): View
    {
        return view('teams.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:80', 'unique:teams,name'],
            'tag' => ['required', 'string', 'min:2', 'max:12', 'alpha_dash', 'unique:teams,tag'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $team = DB::transaction(function () use ($request, $data): Team {
            $team = Team::create($data + ['captain_id' => $request->user()->id]);
            $team->members()->attach($request->user()->id, [
                'member_role' => 'captain',
                'joined_at' => now(),
            ]);
            if ($request->user()->role === 'player') {\n                $request->user()->update(['role' => 'captain']);\n            }

            return $team;
        });

        return redirect()->route('teams.index')->with('success', 'Команда '.$team->name.' создана.');
    }
}
