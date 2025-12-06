<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'employee_id' => '198501012010011001',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Sudirman No. 123',
            'kota' => 'Jakarta',
            'provinsi' => 'DKI Jakarta',
            'bio' => 'System Administrator',
        ]);

        // Team Lead / Project Manager
        $teamLead = User::create([
            'name' => 'John Anderson',
            'email' => 'john.anderson@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_lead',
            'employee_id' => '199001012015011002',
            'phone' => '081234567891',
            'department' => 'IT Department',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $teamLead->id,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1990-05-15',
            'jenis_kelamin' => 'L',
            'spesialisasi' => 'Project Management',
            'skills' => ['Leadership', 'Agile', 'Scrum'],
            'bio' => 'Experienced Project Manager',
        ]);

        // Team Lead 2
        $teamLead2 = User::create([
            'name' => 'Michael Chen',
            'email' => 'michael.chen@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_lead',
            'employee_id' => '199102152015011003',
            'phone' => '081234567892',
            'department' => 'Engineering',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $teamLead2->id,
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1991-02-15',
            'jenis_kelamin' => 'L',
            'spesialisasi' => 'Backend Development',
            'skills' => ['PHP', 'Laravel', 'MySQL', 'Docker'],
            'bio' => 'Backend Team Lead with 8+ years experience',
        ]);

        // Team Lead 3
        $teamLead3 = User::create([
            'name' => 'Emily Rodriguez',
            'email' => 'emily.rodriguez@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_lead',
            'employee_id' => '199203202015011004',
            'phone' => '081234567894',
            'department' => 'Operations',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $teamLead3->id,
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1992-03-20',
            'jenis_kelamin' => 'P',
            'spesialisasi' => 'DevOps & Infrastructure',
            'skills' => ['AWS', 'Kubernetes', 'CI/CD', 'Terraform'],
            'bio' => 'DevOps Team Lead specializing in cloud infrastructure',
        ]);

        // Team Member
        $teamMember = User::create([
            'name' => 'Sarah Williams',
            'email' => 'sarah.williams@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_member',
            'member_id' => '0051234567',
            'phone' => '081234567895',
            'department' => 'Development',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $teamMember->id,
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1998-03-10',
            'jenis_kelamin' => 'P',
            'tahun_masuk' => 2021,
            'tahun_lulus' => 2024,
            'skills' => ['HTML', 'CSS', 'JavaScript', 'React'],
            'bio' => 'Frontend Developer passionate about UI/UX',
        ]);

        // Additional Team Members
        $teamMember2 = User::create([
            'name' => 'David Martinez',
            'email' => 'david.martinez@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_member',
            'member_id' => '0051234568',
            'phone' => '081234567896',
            'department' => 'Development',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $teamMember2->id,
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1997-07-22',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2020,
            'tahun_lulus' => 2024,
            'skills' => ['React', 'Vue.js', 'TypeScript', 'Tailwind CSS'],
            'bio' => 'Full-stack developer focusing on modern frameworks',
        ]);

        $teamMember3 = User::create([
            'name' => 'Lisa Thompson',
            'email' => 'lisa.thompson@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_member',
            'member_id' => '0051234569',
            'phone' => '081234567897',
            'department' => 'Engineering',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $teamMember3->id,
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1999-11-05',
            'jenis_kelamin' => 'P',
            'tahun_masuk' => 2022,
            'tahun_lulus' => 2025,
            'skills' => ['Python', 'Django', 'PostgreSQL', 'Redis'],
            'bio' => 'Backend developer with focus on API development',
        ]);

        $teamMember4 = User::create([
            'name' => 'James Wilson',
            'email' => 'james.wilson@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_member',
            'member_id' => '0051234570',
            'phone' => '081234567898',
            'department' => 'Operations',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $teamMember4->id,
            'tempat_lahir' => 'Medan',
            'tanggal_lahir' => '1996-05-18',
            'jenis_kelamin' => 'L',
            'tahun_masuk' => 2019,
            'tahun_lulus' => 2023,
            'skills' => ['Docker', 'Linux', 'Nginx', 'Jenkins'],
            'bio' => 'DevOps engineer passionate about automation',
        ]);
    }
}
