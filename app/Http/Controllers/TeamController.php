<?php

namespace App\\Http\\Controllers;

use App\\Models\\Team;
use App\\Models\\User;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Validation\\Rule;
use Illuminate\\View\\View;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        $teams = $request->user()->teams()->with(['captain', 'members'])->get();

        return view('teams.index', compact('teams'));
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
            if ($request->user()->role === 'player') {
                $request->user()->update(['role' => 'captain']);
            }

            return $team;
        });

        return redirect()->route('teams.index')->with('success', 'Команда '.$team->name.' создана.');
    }

    public function addMember(Request $request, Team $team): RedirectResponse
    {
        abort_unless($request->user()->isAdmin() || $team->captain_id === $request->user()->id, 403);

        $data = $request->validate([
            'member' => ['required', 'string', 'max:190'],
        ]);

        $member = User::query()
            ->where('email', $data['member'])
            ->orWhere('nickname', $data['member'])
            ->first();

        if (! $member) {
            return back()->withErrors(['member' => 'Игрок с таким email или никнеймом не найден.']);
        }

        if ($team->members()->whereKey($member->id)->exists()) {
            return back()->withErrors(['member' => 'Этот игрок уже состоит в команде.']);
        }

        $team->members()->attach($member->id, ['member_role' => 'player', 'joined_at' => now()]);

        return back()->with('success', 'Игрок '.$member->nickname.' добавлен в состав.');
    }

    public function removeMember(Request $request, Team $team, User $member): RedirectResponse
    {
        abort_unless($request->user()->isAdmin() || $team->captain_id === $request->user()->id, 403);
        abort_if($member->id === $team->captain_id, 422, 'Капитана нельзя удалить из команды.');

        $team->members()->detach($member->id);

        return back()->with('success', 'Игрок удалён из состава.');
    }
}
