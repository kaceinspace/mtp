<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role-specific fields if not exists
            if (!Schema::hasColumn('users', 'user_type')) {
                $table->enum('user_type', ['admin', 'team_lead', 'team_member'])->default('team_member')->after('password');
            }

            if (!Schema::hasColumn('users', 'member_id')) {
                $table->string('member_id')->unique()->nullable()->after('user_type');
            }

            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->unique()->nullable()->after('member_id');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('employee_id');
            }

            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'team')) {
                $table->string('team')->nullable()->after('department');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('remember_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'member_id',
                'employee_id',
                'phone',
                'department',
                'team',
                'is_active'
            ]);
        });
    }
};
