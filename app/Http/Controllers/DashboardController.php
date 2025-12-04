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
            'siswa' => User::where('user_type', 'siswa')->count(),
            'guru' => User::where('user_type', 'guru')->count(),
            'guru_penguji' => User::where('user_type', 'guru_penguji')->count(),
            'admin' => User::where('user_type', 'admin')->count(),
        ];

        return view('pages.admin.dashboard', compact('stats', 'recent_projects'));
    }

    public function siswa()
    {
        $user = auth()->user();

        // Get statistics for siswa dashboard
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

        return view('pages.siswa.dashboard', compact('stats', 'projects', 'activities', 'deadlines', 'team_members'));
    }

    public function guru()
    {
        $user = auth()->user();

        // Get statistics for guru dashboard
        $stats = [
            'my_students' => 0, // Will be implemented
            'supervised_projects' => 0,
            'pending_reviews' => 0,
            'completed_projects' => 0,
        ];

        // Placeholder data
        $supervised_projects = collect([]);
        $students = collect([]);
        $pending_reviews = collect([]);

        return view('pages.guru.dashboard', compact('stats', 'supervised_projects', 'students', 'pending_reviews'));
    }

    public function penguji()
    {
        $user = auth()->user();

        // Get statistics for guru penguji dashboard
        $stats = [
            'projects_to_review' => 0,
            'completed_reviews' => 0,
            'scheduled_presentations' => 0,
            'average_score' => 0,
        ];

        // Placeholder data
        $projects_to_review = collect([]);
        $completed_reviews = collect([]);
        $scheduled_presentations = collect([]);

        return view('pages.penguji.dashboard', compact('stats', 'projects_to_review', 'completed_reviews', 'scheduled_presentations'));
    }

    public function index()
    {
        // Redirect to appropriate dashboard based on user type
        $user = auth()->user();

        return match($user->user_type) {
            'admin' => redirect()->route('dashboard.admin'),
            'guru' => redirect()->route('dashboard.guru'),
            'guru_penguji' => redirect()->route('dashboard.penguji'),
            'siswa' => redirect()->route('dashboard.siswa'),
            default => redirect()->route('dashboard.siswa'),
        };
    }
}
