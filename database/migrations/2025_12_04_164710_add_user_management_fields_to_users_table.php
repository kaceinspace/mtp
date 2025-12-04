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
                $table->enum('user_type', ['admin', 'guru', 'guru_penguji', 'siswa'])->default('siswa')->after('password');
            }

            if (!Schema::hasColumn('users', 'nisn')) {
                $table->string('nisn')->unique()->nullable()->after('user_type');
            }

            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->unique()->nullable()->after('nisn');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('nip');
            }

            if (!Schema::hasColumn('users', 'jurusan')) {
                $table->string('jurusan')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'kelas')) {
                $table->string('kelas')->nullable()->after('jurusan');
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
                'nisn',
                'nip',
                'phone',
                'jurusan',
                'kelas',
                'is_active'
            ]);
        });
    }
};
