<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskWbsController extends Controller
{
    /**
     * Display WBS view for all tasks.
     */
    public function index()
    {
        $user = auth()->user();

        // Get all root level tasks based on user role
        if (Gate::allows('admin')) {
            $tasks = Task::whereNull('parent_id')
                ->with(['children.children.children', 'assignee', 'project'])
                ->orderBy('order')
                ->get();
        } elseif (Gate::allows('team_lead')) {
            $teamLeadTeams = $user->leadingTeams->pluck('id');
            $projectIds = Project::whereIn('team', $teamLeadTeams)
                ->orWhere('created_by', $user->id)
                ->pluck('id');

            $tasks = Task::whereNull('parent_id')
                ->whereIn('project_id', $projectIds)
                ->with(['children.children.children', 'assignee', 'project'])
                ->orderBy('order')
                ->get();
        } else {
            $projectIds = $user->projects()->pluck('projects.id');
            $tasks = Task::whereNull('parent_id')
                ->whereIn('project_id', $projectIds)
                ->with(['children.children.children', 'assignee', 'project'])
                ->orderBy('order')
                ->get();
        }

        // Get all users for assignment
        $users = User::all();

        return view('pages.tasks.wbs', compact('tasks', 'users'));
    }
}

