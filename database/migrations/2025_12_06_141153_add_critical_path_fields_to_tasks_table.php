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
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('early_start')->nullable()->after('calculated_end_date'); // Earliest start time
            $table->integer('early_finish')->nullable()->after('early_start'); // Earliest finish time
            $table->integer('late_start')->nullable()->after('early_finish'); // Latest start time
            $table->integer('late_finish')->nullable()->after('late_start'); // Latest finish time
            $table->integer('total_float')->nullable()->after('late_finish'); // Total float/slack time
            $table->boolean('is_critical')->default(false)->after('total_float'); // Is on critical path
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'early_start',
                'early_finish',
                'late_start',
                'late_finish',
                'total_float',
                'is_critical'
            ]);
        });
    }
};
