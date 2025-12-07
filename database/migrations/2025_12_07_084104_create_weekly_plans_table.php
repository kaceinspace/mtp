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
        Schema::create('weekly_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Week identification
            $table->date('week_start_date');
            $table->date('week_end_date');
            $table->integer('week_number'); // Week of year (1-53)
            $table->integer('year');
            
            // Planning summary
            $table->text('objectives')->nullable(); // Main objectives for the week
            $table->text('key_activities')->nullable(); // Key activities planned
            $table->decimal('planned_weight_total', 5, 2)->default(0); // Total bobot planned
            
            // Status
            $table->enum('status', ['draft', 'submitted', 'approved', 'in-progress', 'completed'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Summary (filled at week end)
            $table->decimal('actual_weight_total', 5, 2)->default(0);
            $table->text('achievements')->nullable();
            $table->text('major_problems')->nullable();
            $table->text('next_week_plan')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['project_id', 'week_start_date']);
            $table->index(['year', 'week_number']);
            $table->unique(['project_id', 'week_start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_plans');
    }
};
