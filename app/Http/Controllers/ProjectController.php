<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Admin can see all projects
        if (Gate::allows('admin')) {
            $projects = Project::with(['creator', 'members'])
                ->latest()
                ->paginate(10);
        }
        // Team Lead can only see projects from their team
        elseif (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');

            $projects = Project::with(['creator', 'members'])
                ->whereIn('team', $teamLeadTeams)
                ->latest()
                ->paginate(10);
        }
        // Team members see only their assigned projects
        else {
            $projects = $user->projects()
                ->with(['creator', 'members'])
                ->latest()
                ->paginate(10);
        }

        return view('pages.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin and team_lead can create projects
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'This action is unauthorized.');
        }

        $users = User::where('user_type', '!=', 'admin')->get();

        return view('pages.projects.create', compact('users'));
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'team' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,ongoing,completed,on-hold',
            'priority' => 'required|in:low,medium,high,critical',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'department' => $validated['department'] ?? null,
            'team' => $validated['team'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'created_by' => auth()->id(),
        ]);

        // Attach members if provided
        if (!empty($validated['members'])) {
            $project->members()->attach($validated['members']);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['creator', 'members', 'tasks.assignee']);

        return view('pages.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Check authorization
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::where('user_type', '!=', 'admin')->get();
        $project->load('members');

        return view('pages.projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'nullable|string|max:255',
            'team' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,ongoing,completed,on-hold',
            'priority' => 'required|in:low,medium,high,critical',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $project->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'department' => $validated['department'] ?? null,
            'team' => $validated['team'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
        ]);

        // Sync members
        if (isset($validated['members'])) {
            $project->members()->sync($validated['members']);
        } else {
            $project->members()->detach();
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        Gate::authorize('admin');

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
