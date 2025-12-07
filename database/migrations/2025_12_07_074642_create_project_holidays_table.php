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
        Schema::create('project_holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('name');
            $table->string('type')->default('holiday'); // holiday, non-working-day, custom
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false); // For annual holidays
            $table->timestamps();

            // Indexes
            $table->index(['project_id', 'date']);
            $table->unique(['project_id', 'date']);
        });

        // Working days configuration table
        Schema::create('project_working_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->boolean('monday')->default(true);
            $table->boolean('tuesday')->default(true);
            $table->boolean('wednesday')->default(true);
            $table->boolean('thursday')->default(true);
            $table->boolean('friday')->default(true);
            $table->boolean('saturday')->default(false);
            $table->boolean('sunday')->default(false);
            $table->time('work_start_time')->default('09:00:00');
            $table->time('work_end_time')->default('17:00:00');
            $table->decimal('hours_per_day', 4, 2)->default(8.00);
            $table->timestamps();

            $table->unique('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_working_days');
        Schema::dropIfExists('project_holidays');
    }
};
