<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $completedProjects = Project::where('status', 'completed')->count();

        // Get statistics for admin dashboard
        $stats = [
            'total_users' => $totalUsers,
            'active_users' => User::where('is_active', true)->count(),
            'total_projects' => $totalProjects,
            'ongoing_projects' => Project::where('status', 'ongoing')->count(),
            'completed_projects' => $completedProjects,
            'total_tasks' => $totalTasks,
            'pending_tasks' => Task::where('status', 'todo')->count(),
            'completion_rate' => $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100) : 0,
        ];

        // Get recent projects
        $recent_projects = Project::with('creator')
            ->latest()
            ->take(5)
            ->get();

        // Get user distribution with counts and percentages
        $teamMemberCount = User::where('user_type', 'team_member')->count();
        $teamLeadCount = User::where('user_type', 'team_lead')->count();
        $adminCount = User::where('user_type', 'admin')->count();

        $stats['team_member_count'] = $teamMemberCount;
        $stats['team_lead_count'] = $teamLeadCount;
        $stats['admin_count'] = $adminCount;

        $stats['team_member_percentage'] = $totalUsers > 0 ? round(($teamMemberCount / $totalUsers) * 100) : 0;
        $stats['team_lead_percentage'] = $totalUsers > 0 ? round(($teamLeadCount / $totalUsers) * 100) : 0;
        $stats['admin_percentage'] = $totalUsers > 0 ? round(($adminCount / $totalUsers) * 100) : 0;

        // Get recent activities (all activities for admin)
        $recent_activities = Activity::with(['user', 'project'])
            ->latest()
            ->take(15)
            ->get();

        return view('pages.admin.dashboard', compact('stats', 'recent_projects', 'recent_activities'));
    }

    public function teamMember()
    {
        $user = auth()->user();

        // Get projects where user is a member
        $myProjectIds = $user->projects()->pluck('projects.id');

        // Get assigned tasks
        $myTasks = Task::where('assigned_to', $user->id);
        $activeTasks = (clone $myTasks)->whereIn('status', ['todo', 'in-progress'])->get();
        $completedTasks = (clone $myTasks)->where('status', 'completed')->count();
        $overdueTasks = (clone $myTasks)
            ->whereIn('status', ['todo', 'in-progress'])
            ->where('due_date', '<', now())
            ->count();

        // Get statistics
        $stats = [
            'my_projects' => $myProjectIds->count(),
            'active_projects' => $user->projects()->whereIn('status', ['planning', 'ongoing'])->count(),
            'active_tasks' => $activeTasks->count(),
            'overdue_tasks' => $overdueTasks,
            'completed_tasks' => $completedTasks,
            'total_tasks' => Task::where('assigned_to', $user->id)->count(),
        ];

        // Get my projects with details
        $projects = Project::whereIn('id', $myProjectIds)
            ->with(['creator', 'members'])
            ->latest()
            ->take(5)
            ->get();

        // Get upcoming deadlines
        $deadlines = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in-progress'])
            ->whereNotNull('due_date')
            ->where('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Get recent activities from my tasks
        $activities = Task::where('assigned_to', $user->id)
            ->latest('updated_at')
            ->take(10)
            ->get();

        // Get recent activities from my projects
        $recent_activities = Activity::with(['user', 'project'])
            ->whereIn('project_id', $myProjectIds)
            ->latest()
            ->take(15)
            ->get();

        // Get team members from my projects
        $team_members = User::whereHas('projects', function($query) use ($myProjectIds) {
            $query->whereIn('projects.id', $myProjectIds);
        })
        ->where('id', '!=', $user->id)
        ->with('profile')
        ->take(10)
        ->get();

        return view('pages.team_member.dashboard', compact('stats', 'projects', 'activities', 'deadlines', 'team_members', 'recent_activities'));
    }

    public function teamLead()
    {
        $user = auth()->user();

        // Get teams where user is a team lead
        $myTeamIds = $user->leadingTeams->pluck('id');

        // Get projects for my teams
        $myProjects = Project::whereIn('team', $myTeamIds)->with(['members', 'tasks']);

        // Get statistics
        $stats = [
            'my_teams' => $myTeamIds->count(),
            'total_projects' => (clone $myProjects)->count(),
            'ongoing_projects' => (clone $myProjects)->where('status', 'ongoing')->count(),
            'completed_projects' => (clone $myProjects)->where('status', 'completed')->count(),
            'total_tasks' => Task::whereHas('project', function($query) use ($myTeamIds) {
                $query->whereIn('team', $myTeamIds);
            })->count(),
            'pending_tasks' => Task::whereHas('project', function($query) use ($myTeamIds) {
                $query->whereIn('team', $myTeamIds);
            })->where('status', 'todo')->count(),
        ];

        // Get projects for display
        $projects = Project::whereIn('team', $myTeamIds)
            ->with(['creator', 'members', 'teamInfo'])
            ->latest()
            ->take(6)
            ->get();

        // Get team members
        $team_members = User::whereHas('teams', function($query) use ($myTeamIds) {
            $query->whereIn('teams.id', $myTeamIds);
        })
        ->where('id', '!=', $user->id)
        ->with('profile')
        ->get();

        // Get pending/high priority tasks
        $pending_tasks = Task::whereHas('project', function($query) use ($myTeamIds) {
            $query->whereIn('team', $myTeamIds);
        })
        ->whereIn('status', ['todo', 'in-progress'])
        ->where('priority', 'critical')
        ->orWhere(function($query) use ($myTeamIds) {
            $query->whereHas('project', function($q) use ($myTeamIds) {
                $q->whereIn('team', $myTeamIds);
            })
            ->where('due_date', '<', now()->addDays(3));
        })
        ->with(['project', 'assignedTo'])
        ->orderBy('priority', 'desc')
        ->orderBy('due_date', 'asc')
        ->take(10)
        ->get();

        // Get recent activities from my teams' projects
        $recent_activities = Activity::with(['user', 'project'])
            ->whereHas('project', function($query) use ($myTeamIds) {
                $query->whereIn('team', $myTeamIds);
            })
            ->latest()
            ->take(15)
            ->get();

        return view('pages.team_lead.dashboard', compact('stats', 'projects', 'team_members', 'pending_tasks', 'recent_activities'));
    }

    public function index()
    {
        // Redirect to appropriate dashboard based on user type
        $user = auth()->user();

        return match($user->user_type) {
            'admin' => redirect()->route('dashboard.admin'),
            'team_lead' => redirect()->route('dashboard.team_lead'),
            'team_member' => redirect()->route('dashboard.team_member'),
            default => redirect()->route('dashboard.team_member'),
        };
    }
}
