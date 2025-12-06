<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Admin can see all tasks
        if (Gate::allows('admin')) {
            $tasks = Task::with(['project', 'assignee'])
                ->latest()
                ->paginate(20);
        }
        // Team Lead can only see tasks from their team's projects
        elseif (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');

            $tasks = Task::with(['project', 'assignee'])
                ->whereHas('project', function($query) use ($teamLeadTeams) {
                    $query->whereIn('team', $teamLeadTeams);
                })
                ->latest()
                ->paginate(20);
        }
        // Team members see only their assigned tasks
        else {
            $tasks = Task::where('assigned_to', $user->id)
                ->with(['project', 'assignee'])
                ->latest()
                ->paginate(20);
        }

        return view('pages.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Only admin and team_lead can create tasks
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'This action is unauthorized.');
        }

        $user = auth()->user();

        // Admin can see all projects
        if (Gate::allows('admin')) {
            $projects = Project::latest()->get();
        }
        // Team Lead can only see their team's projects
        else {
            $teamLeadTeams = $user->leadingTeams->pluck('id');
            $projects = Project::whereIn('team', $teamLeadTeams)->latest()->get();
        }

        $users = User::where('user_type', '!=', 'admin')->get();

        // Get selected project if provided
        $selectedProjectId = $request->query('project_id');

        return view('pages.tasks.create', compact('projects', 'users', 'selectedProjectId'));
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
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in-progress,review,completed',
            'priority' => 'required|in:low,medium,high,critical',
            'due_date' => 'nullable|date',
        ]);

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['project', 'assignee']);

        return view('pages.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // Check authorization
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            abort(403, 'Unauthorized action.');
        }

        $user = auth()->user();

        // Admin can see all projects
        if (Gate::allows('admin')) {
            $projects = Project::latest()->get();
        }
        // Team Lead can only see their team's projects
        else {
            $teamLeadTeams = $user->leadingTeams->pluck('id');
            $projects = Project::whereIn('team', $teamLeadTeams)->latest()->get();
        }

        $users = User::where('user_type', '!=', 'admin')->get();

        return view('pages.tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // Team members can only update status of their own tasks
        $user = auth()->user();

        if ($user->user_type === 'team_member') {
            // Team member can only update their assigned tasks and only the status
            if ($task->assigned_to !== $user->id) {
                abort(403, 'You can only update your own tasks.');
            }

            $validated = $request->validate([
                'status' => 'required|in:todo,in-progress,review,completed',
            ]);

            $task->update([
                'status' => $validated['status'],
                'completed_at' => $validated['status'] === 'completed' ? now() : null,
            ]);
        } else {
            // Admin & Team Lead can update everything
            if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'assigned_to' => 'nullable|exists:users,id',
                'status' => 'required|in:todo,in-progress,review,completed',
                'priority' => 'required|in:low,medium,high,critical',
                'due_date' => 'nullable|date',
            ]);

            $task->update([
                'project_id' => $validated['project_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'assigned_to' => $validated['assigned_to'] ?? null,
                'status' => $validated['status'],
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'] ?? null,
                'completed_at' => $validated['status'] === 'completed' ? now() : null,
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('admin');

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Display tasks in Kanban board view.
     */
    public function kanban()
    {
        $user = auth()->user();

        // Admin can see all tasks
        if (Gate::allows('admin')) {
            $tasks = Task::with(['project', 'assignee'])->get();
        }
        // Team Lead can only see tasks from their team's projects
        elseif (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');

            $tasks = Task::with(['project', 'assignee'])
                ->whereHas('project', function($query) use ($teamLeadTeams) {
                    $query->whereIn('team', $teamLeadTeams);
                })
                ->get();
        }
        // Team members see only their assigned tasks
        else {
            $tasks = Task::where('assigned_to', $user->id)
                ->with(['project', 'assignee'])
                ->get();
        }

        // Group tasks by status
        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo'),
            'in-progress' => $tasks->where('status', 'in-progress'),
            'review' => $tasks->where('status', 'review'),
            'completed' => $tasks->where('status', 'completed'),
        ];

        $projects = Project::all();
        $users = User::where('user_type', '!=', 'admin')->get();

        return view('pages.tasks.kanban', compact('tasksByStatus', 'projects', 'users'));
    }

    /**
     * Update task status via AJAX for Kanban drag & drop.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in-progress,review,completed',
        ]);

        // Authorization: Admin and team_lead can update any task, team_member can update own tasks
        if (!Gate::allows('admin') && !Gate::allows('team_lead')) {
            if ($task->assigned_to !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        $task->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully!',
            'task' => $task->load(['project', 'assignee']),
        ]);
    }
}
