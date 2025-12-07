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
        Schema::create('task_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            
            // Progress tracking
            $table->decimal('progress_percentage', 5, 2)->default(0); // 0-100
            $table->decimal('planned_percentage', 5, 2)->default(0); // What was planned for this week
            $table->decimal('actual_percentage', 5, 2)->default(0); // What actually completed
            
            // Weight tracking (bobot)
            $table->decimal('planned_weight', 5, 2)->default(0); // Bobot planned this week
            $table->decimal('actual_weight', 5, 2)->default(0); // Bobot actually completed
            
            // Dates
            $table->date('week_start_date');
            $table->date('week_end_date');
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            
            // Status & notes
            $table->enum('status', ['on-track', 'at-risk', 'delayed', 'completed'])->default('on-track');
            $table->text('notes')->nullable();
            $table->text('issues')->nullable(); // Problems encountered
            $table->text('proposed_solutions')->nullable();
            
            // Deviation tracking
            $table->decimal('deviation_percentage', 5, 2)->default(0); // planned - actual
            $table->integer('deviation_days')->default(0); // Schedule deviation in days
            
            $table->timestamps();
            
            // Indexes
            $table->index(['task_id', 'week_start_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_progress');
    }
};
