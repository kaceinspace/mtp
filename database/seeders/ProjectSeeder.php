<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@simpro.app')->first();
        $teamLeads = User::where('user_type', 'team_lead')->get();
        $teamMembers = User::where('user_type', 'team_member')->get();

        // Get teams
        $teams = \App\Models\Team::all();
        if ($teams->isEmpty()) {
            $this->command->warn('Please seed teams first before running ProjectSeeder.');
            return;
        }

        // Project 1: Website Redesign (Team 1 - Frontend Development)
        $project1 = Project::create([
            'title' => 'Company Website Redesign',
            'description' => 'Redesign the company website with modern UI/UX and improve user experience. Implement responsive design and optimize for mobile devices.',
            'department' => 'Engineering',
            'team' => $teams->first()->id, // Frontend Team
            'start_date' => now()->subDays(30),
            'end_date' => now()->addDays(30),
            'status' => 'ongoing',
            'priority' => 'high',
            'created_by' => $teamLeads->first()->id,
        ]);

        // Attach team members
        $project1->members()->attach([$teamMembers->first()->id, $teamMembers->skip(1)->first()->id]);

        // Tasks for Project 1
        Task::create([
            'project_id' => $project1->id,
            'title' => 'Design Homepage Mockup',
            'description' => 'Create modern homepage design with new color scheme',
            'assigned_to' => $teamMembers->first()->id,
            'status' => 'completed',
            'priority' => 'high',
            'due_date' => now()->subDays(20),
            'completed_at' => now()->subDays(18),
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Develop Frontend Components',
            'description' => 'Build reusable React components for the new design',
            'assigned_to' => $teamMembers->first()->id,
            'status' => 'in-progress',
            'priority' => 'high',
            'due_date' => now()->addDays(10),
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Backend API Integration',
            'description' => 'Integrate frontend with existing backend APIs',
            'assigned_to' => $teamMembers->skip(1)->first()->id,
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => now()->addDays(20),
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Mobile Responsiveness Testing',
            'description' => 'Test website on various mobile devices and screen sizes',
            'assigned_to' => $teamMembers->first()->id,
            'status' => 'review',
            'priority' => 'medium',
            'due_date' => now()->addDays(25),
        ]);

        // Project 2: Mobile App Development (Team 2 - Backend Development)
        $project2 = Project::create([
            'title' => 'Mobile App Development',
            'description' => 'Develop a cross-platform mobile application for project tracking and team collaboration.',
            'department' => 'Engineering',
            'team' => $teams->skip(1)->first()->id ?? $teams->first()->id, // Backend Team
            'start_date' => now()->subDays(60),
            'end_date' => now()->addDays(60),
            'status' => 'ongoing',
            'priority' => 'critical',
            'created_by' => $teamLeads->skip(1)->first()->id ?? $teamLeads->first()->id,
        ]);

        $project2->members()->attach([
            $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
            $teamMembers->skip(3)->first()->id ?? $teamMembers->skip(1)->first()->id,
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Setup Development Environment',
            'description' => 'Configure React Native and necessary tools',
            'assigned_to' => $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
            'status' => 'completed',
            'priority' => 'high',
            'due_date' => now()->subDays(50),
            'completed_at' => now()->subDays(48),
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Implement Authentication',
            'description' => 'Build login and registration screens',
            'assigned_to' => $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
            'status' => 'in-progress',
            'priority' => 'critical',
            'due_date' => now()->addDays(5),
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Design App UI/UX',
            'description' => 'Create intuitive and modern app interface',
            'assigned_to' => $teamMembers->skip(3)->first()->id ?? $teamMembers->first()->id,
            'status' => 'review',
            'priority' => 'high',
            'due_date' => now()->addDays(15),
        ]);

        // Project 3: Database Optimization (Team 2 - Backend Development)
        $project3 = Project::create([
            'title' => 'Database Performance Optimization',
            'description' => 'Optimize database queries and improve overall system performance. Implement caching strategies.',
            'department' => 'Engineering',
            'team' => $teams->skip(1)->first()->id ?? $teams->first()->id, // Backend Team
            'start_date' => now()->subDays(15),
            'end_date' => now()->addDays(15),
            'status' => 'planning',
            'priority' => 'medium',
            'created_by' => $teamLeads->skip(1)->first()->id ?? $teamLeads->first()->id,
        ]);

        $project3->members()->attach([
            $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
        ]);

        Task::create([
            'project_id' => $project3->id,
            'title' => 'Analyze Current Performance',
            'description' => 'Run performance tests and identify bottlenecks',
            'assigned_to' => $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
            'status' => 'todo',
            'priority' => 'high',
            'due_date' => now()->addDays(5),
        ]);

        Task::create([
            'project_id' => $project3->id,
            'title' => 'Implement Database Indexing',
            'description' => 'Add indexes to frequently queried columns',
            'assigned_to' => $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => now()->addDays(10),
        ]);

        // Project 4: Documentation Update (Team 1 - Frontend Development)
        $project4 = Project::create([
            'title' => 'System Documentation Update',
            'description' => 'Update all system documentation, API docs, and user manuals.',
            'department' => 'Engineering',
            'team' => $teams->first()->id, // Frontend Team
            'start_date' => now()->subDays(90),
            'end_date' => now()->subDays(30),
            'status' => 'completed',
            'priority' => 'low',
            'created_by' => $teamLeads->first()->id,
        ]);

        $project4->members()->attach([
            $teamMembers->first()->id,
            $teamMembers->skip(1)->first()->id ?? $teamMembers->first()->id,
        ]);

        Task::create([
            'project_id' => $project4->id,
            'title' => 'Update API Documentation',
            'description' => 'Document all API endpoints with examples',
            'assigned_to' => $teamMembers->first()->id,
            'status' => 'completed',
            'priority' => 'medium',
            'due_date' => now()->subDays(60),
            'completed_at' => now()->subDays(55),
        ]);

        Task::create([
            'project_id' => $project4->id,
            'title' => 'Write User Manual',
            'description' => 'Create comprehensive user manual with screenshots',
            'assigned_to' => $teamMembers->first()->id,
            'status' => 'completed',
            'priority' => 'medium',
            'due_date' => now()->subDays(40),
            'completed_at' => now()->subDays(35),
        ]);

        // Project 5: Security Audit (Team 3 - DevOps)
        $project5 = Project::create([
            'title' => 'Security Audit & Enhancement',
            'description' => 'Conduct comprehensive security audit and implement security enhancements.',
            'department' => 'Operations',
            'team' => $teams->skip(2)->first()->id ?? $teams->first()->id, // DevOps Team
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(40),
            'status' => 'planning',
            'priority' => 'critical',
            'created_by' => $teamLeads->skip(2)->first()->id ?? $admin->id,
        ]);

        $project5->members()->attach([
            $teamMembers->skip(3)->first()->id ?? $teamMembers->first()->id,
        ]);

        Task::create([
            'project_id' => $project5->id,
            'title' => 'Vulnerability Assessment',
            'description' => 'Scan system for security vulnerabilities',
            'assigned_to' => $teamMembers->skip(3)->first()->id ?? $teamMembers->first()->id,
            'status' => 'todo',
            'priority' => 'critical',
            'due_date' => now()->addDays(15),
        ]);

        Task::create([
            'project_id' => $project5->id,
            'title' => 'Implement Security Patches',
            'description' => 'Apply necessary security patches and updates',
            'assigned_to' => $teamMembers->skip(3)->first()->id ?? $teamMembers->first()->id,
            'status' => 'todo',
            'priority' => 'critical',
            'due_date' => now()->addDays(30),
        ]);

        // Project 6: On Hold Project (Team 2 - Backend)
        $project6 = Project::create([
            'title' => 'E-commerce Platform Development',
            'description' => 'Build a complete e-commerce platform with payment integration.',
            'department' => 'Engineering',
            'team' => $teams->skip(1)->first()->id ?? $teams->first()->id, // Backend Team
            'start_date' => now()->subDays(45),
            'end_date' => now()->addDays(90),
            'status' => 'on-hold',
            'priority' => 'medium',
            'created_by' => $teamLeads->skip(1)->first()->id ?? $teamLeads->first()->id,
        ]);

        $project6->members()->attach([
            $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
        ]);

        Task::create([
            'project_id' => $project6->id,
            'title' => 'Product Catalog Setup',
            'description' => 'Design and implement product catalog structure',
            'assigned_to' => $teamMembers->skip(2)->first()->id ?? $teamMembers->first()->id,
            'status' => 'in-progress',
            'priority' => 'medium',
            'due_date' => now()->addDays(45),
        ]);
    }
}

