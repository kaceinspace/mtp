<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Order matters: Users -> Teams -> Projects
        $this->call(UserSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(ProjectSeeder::class);
    }
}

