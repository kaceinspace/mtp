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

        // Team Member
        $teamMember = User::create([
            'name' => 'Sarah Williams',
            'email' => 'sarah.williams@simpro.app',
            'password' => Hash::make('password'),
            'user_type' => 'team_member',
            'member_id' => '0051234567',
            'phone' => '081234567893',
            'department' => 'Development',
            'team' => 'Team Alpha',
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
    }
}
