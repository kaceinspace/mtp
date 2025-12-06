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
            // Add parent task relationship for hierarchical structure
            $table->foreignId('parent_id')->nullable()->after('project_id')
                ->constrained('tasks')->onDelete('cascade');

            // Add order column for task sorting within same level
            $table->integer('order')->default(0)->after('parent_id');

            // Add level for quick hierarchy depth check
            $table->integer('level')->default(0)->after('order');

            // Add WBS code (e.g., 1.2.3)
            $table->string('wbs_code', 50)->nullable()->after('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'order', 'level', 'wbs_code']);
        });
    }
};
