<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        // Get statistics for admin dashboard
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_projects' => 0, // Will be implemented when Project model exists
            'total_tasks' => 0, // Will be implemented when Task model exists
            'completion_rate' => 0, // Will be calculated when data exists
        ];

        // Get recent projects (placeholder for now)
        $recent_projects = collect([
            // Example data structure:
            // (object)[
            //     'title' => 'Website Redesign',
            //     'creator' => 'John Doe',
            //     'status' => 'in_progress',
            //     'created_at' => now()->subDays(2),
            // ],
        ]);

        // Get user distribution
        $stats['user_distribution'] = [
            'team_member' => User::where('user_type', 'team_member')->count(),
            'team_lead' => User::where('user_type', 'team_lead')->count(),
            'admin' => User::where('user_type', 'admin')->count(),
        ];

        return view('pages.admin.dashboard', compact('stats', 'recent_projects'));
    }

    public function teamMember()
    {
        $user = auth()->user();

        // Get statistics for team member dashboard
        $stats = [
            'my_projects' => 0, // Will be implemented when Project model exists
            'active_projects' => 0,
            'active_tasks' => 0, // Will be implemented when Task model exists
            'overdue_tasks' => 0,
            'completed_tasks' => 0,
            'total_tasks' => 0,
            'achievement_points' => 0, // Will be implemented when Achievement system exists
        ];

        // Get my projects (placeholder for now)
        $projects = collect([
            // Example data structure:
            // (object)[
            //     'title' => 'Mobile App Development',
            //     'description' => 'Building a mobile app for school management',
            //     'status' => 'in_progress',
            //     'progress' => 65,
            //     'members_count' => 4,
            //     'created_at' => now()->subDays(5),
            // ],
        ]);

        // Get recent activities (placeholder for now)
        $activities = collect([
            // Example data structure:
            // (object)[
            //     'type' => 'task_completed',
            //     'description' => 'Menyelesaikan task "Design Homepage"',
            //     'created_at' => now()->subHours(2),
            // ],
        ]);

        // Get upcoming deadlines (placeholder for now)
        $deadlines = collect([
            // Example data structure:
            // (object)[
            //     'title' => 'Submit Design Mockup',
            //     'project' => 'Website Redesign',
            //     'due_date' => now()->addDays(2),
            // ],
        ]);

        // Get team members (placeholder for now)
        $team_members = collect([
            // Example data structure:
            // (object)[
            //     'name' => 'Budi Santoso',
            //     'role' => 'Frontend Developer',
            //     'avatar' => 'https://ui-avatars.com/api/?name=Budi+Santoso',
            //     'is_online' => true,
            // ],
        ]);

        return view('pages.team_member.dashboard', compact('stats', 'projects', 'activities', 'deadlines', 'team_members'));
    }

    public function teamLead()
    {
        $user = auth()->user();

        // Get statistics for team lead dashboard
        $stats = [
            'my_team_members' => 0, // Will be implemented
            'supervised_projects' => 0,
            'pending_reviews' => 0,
            'completed_projects' => 0,
        ];

        // Placeholder data
        $supervised_projects = collect([]);
        $team_members = collect([]);
        $pending_reviews = collect([]);

        return view('pages.team_lead.dashboard', compact('stats', 'supervised_projects', 'team_members', 'pending_reviews'));
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
