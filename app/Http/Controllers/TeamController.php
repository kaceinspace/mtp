<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Admin can see all teams
        if (Gate::allows('admin')) {
            $teams = Team::with(['teamLead', 'members'])
                ->latest()
                ->paginate(12);
        } elseif (Gate::allows('team_lead')) {
            // Team leads see teams they lead or are members of
            $teams = Team::with(['teamLead', 'members'])
                ->where('team_lead_id', $user->id)
                ->orWhereHas('members', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->paginate(12);
        } else {
            // Team members see only their teams
            $teams = $user->teams()
                ->with(['teamLead', 'members'])
                ->latest()
                ->paginate(12);
        }

        return view('pages.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin and team_lead can create teams
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'This action is unauthorized.');
        }

        $users = User::where('user_type', '!=', 'admin')->get();
        $teamLeads = User::where('user_type', 'team_lead')->get();

        return view('pages.teams.create', compact('users', 'teamLeads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'This action is unauthorized.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_lead_id' => 'nullable|exists:users,id',
            'department' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'team_lead_id' => $validated['team_lead_id'] ?? null,
            'department' => $validated['department'] ?? null,
            'status' => $validated['status'],
        ]);

        // Attach members if provided
        if (!empty($validated['members'])) {
            $team->members()->attach($validated['members']);
        }

        // Auto-add team lead as member if specified
        if ($validated['team_lead_id'] && !in_array($validated['team_lead_id'], $validated['members'] ?? [])) {
            $team->members()->attach($validated['team_lead_id'], ['role' => 'lead']);
        }

        return redirect()->route('teams.index')
            ->with('success', 'Team created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        $team->load(['teamLead', 'members.profile']);

        return view('pages.teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        // Check authorization
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'Unauthorized action.');
        }

        // Team lead can only edit their own team
        if (Gate::allows('team_lead') && $team->team_lead_id !== auth()->id()) {
            abort(403, 'You can only edit teams you lead.');
        }

        $users = User::where('user_type', '!=', 'admin')->get();
        $teamLeads = User::where('user_type', 'team_lead')->get();
        $team->load('members');

        return view('pages.teams.edit', compact('team', 'users', 'teamLeads'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'Unauthorized action.');
        }

        // Team lead can only edit their own team
        if (Gate::allows('team_lead') && $team->team_lead_id !== auth()->id()) {
            abort(403, 'You can only edit teams you lead.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_lead_id' => 'nullable|exists:users,id',
            'department' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'team_lead_id' => $validated['team_lead_id'] ?? null,
            'department' => $validated['department'] ?? null,
            'status' => $validated['status'],
        ]);

        // Sync members
        if (isset($validated['members'])) {
            $team->members()->sync($validated['members']);
        } else {
            $team->members()->detach();
        }

        // Auto-add team lead as member if specified
        if ($validated['team_lead_id'] && !in_array($validated['team_lead_id'], $validated['members'] ?? [])) {
            $team->members()->syncWithoutDetaching([$validated['team_lead_id'] => ['role' => 'lead']]);
        }

        return redirect()->route('teams.index')
            ->with('success', 'Team updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        Gate::authorize('admin');

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully!');
    }
}
