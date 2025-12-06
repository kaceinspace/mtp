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
        // Create pivot table for task dependencies (many-to-many)
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('depends_on_task_id')->constrained('tasks')->onDelete('cascade');
            $table->enum('dependency_type', ['finish-to-start', 'start-to-start', 'finish-to-finish', 'start-to-finish'])->default('finish-to-start');
            $table->integer('lag_days')->default(0); // Delay in days (can be negative for lead time)
            $table->timestamps();

            // Prevent circular dependencies at database level
            $table->unique(['task_id', 'depends_on_task_id']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            // Add estimated duration for dependency calculation
            $table->integer('estimated_duration')->default(1)->after('due_date'); // in days
            $table->date('calculated_start_date')->nullable()->after('estimated_duration');
            $table->date('calculated_end_date')->nullable()->after('calculated_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['estimated_duration', 'calculated_start_date', 'calculated_end_date']);
        });

        Schema::dropIfExists('task_dependencies');
    }
};
