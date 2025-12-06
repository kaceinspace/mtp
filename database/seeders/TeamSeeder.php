<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users by role
        $teamLeads = User::where('user_type', 'team_lead')->get();
        $teamMembers = User::where('user_type', 'team_member')->get();

        if ($teamLeads->isEmpty() || $teamMembers->isEmpty()) {
            $this->command->warn('Please seed users first before running TeamSeeder.');
            return;
        }

        // Team 1: Frontend Development Team
        $frontendTeam = Team::create([
            'name' => 'Frontend Development Team',
            'description' => 'Responsible for creating responsive and interactive user interfaces using modern frameworks like React, Vue, and Angular. Focus on user experience and design implementation.',
            'team_lead_id' => $teamLeads->first()->id,
            'department' => 'Engineering',
            'status' => 'active',
        ]);

        // Attach team lead as member with role 'lead'
        $frontendTeam->members()->attach($teamLeads->first()->id, ['role' => 'lead']);

        // Attach 3 team members
        $frontendMembers = $teamMembers->take(3);
        foreach ($frontendMembers as $member) {
            $frontendTeam->members()->attach($member->id, ['role' => 'member']);
        }

        // Team 2: Backend Development Team
        $backendTeam = Team::create([
            'name' => 'Backend Development Team',
            'description' => 'Handles server-side logic, database design, API development, and system architecture. Works with Laravel, Node.js, and microservices.',
            'team_lead_id' => $teamLeads->skip(1)->first()->id ?? $teamLeads->first()->id,
            'department' => 'Engineering',
            'status' => 'active',
        ]);

        $backendTeamLead = $teamLeads->skip(1)->first() ?? $teamLeads->first();
        $backendTeam->members()->attach($backendTeamLead->id, ['role' => 'lead']);

        // Attach 4 team members (skip those already in frontend)
        $backendMembers = $teamMembers->skip(3)->take(4);
        foreach ($backendMembers as $member) {
            $backendTeam->members()->attach($member->id, ['role' => 'member']);
        }

        // Team 3: DevOps & Infrastructure Team
        $devopsTeam = Team::create([
            'name' => 'DevOps & Infrastructure',
            'description' => 'Manages cloud infrastructure, CI/CD pipelines, monitoring, and deployment automation. Ensures system reliability and scalability.',
            'team_lead_id' => $teamLeads->skip(2)->first()->id ?? $teamLeads->first()->id,
            'department' => 'Operations',
            'status' => 'active',
        ]);

        $devopsTeamLead = $teamLeads->skip(2)->first() ?? $teamLeads->first();
        $devopsTeam->members()->attach($devopsTeamLead->id, ['role' => 'lead']);

        // Attach 2 team members
        $devopsMembers = $teamMembers->skip(7)->take(2);
        foreach ($devopsMembers as $member) {
            $devopsTeam->members()->attach($member->id, ['role' => 'member']);
        }

        // Team 4: Quality Assurance Team (Inactive - being restructured)
        $qaTeam = Team::create([
            'name' => 'Quality Assurance Team',
            'description' => 'Ensures software quality through manual and automated testing. Currently undergoing team restructuring and process improvements.',
            'team_lead_id' => null, // No team lead assigned yet
            'department' => 'Quality',
            'status' => 'inactive',
        ]);

        // Attach 2 team members without a lead
        $qaMembers = $teamMembers->skip(9)->take(2);
        foreach ($qaMembers as $member) {
            $qaTeam->members()->attach($member->id, ['role' => 'member']);
        }

        $this->command->info('Teams seeded successfully!');
        $this->command->info('- Frontend Development Team: ' . ($frontendMembers->count() + 1) . ' members');
        $this->command->info('- Backend Development Team: ' . ($backendMembers->count() + 1) . ' members');
        $this->command->info('- DevOps & Infrastructure: ' . ($devopsMembers->count() + 1) . ' members');
        $this->command->info('- Quality Assurance Team: ' . $qaMembers->count() . ' members (inactive)');
    }
}
