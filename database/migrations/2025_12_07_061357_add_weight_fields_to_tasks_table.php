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
            $table->decimal('weight', 5, 2)->default(0)->after('due_date');
            $table->decimal('weight_percentage', 5, 2)->default(0)->after('weight');
            $table->boolean('is_weight_locked')->default(false)->after('weight_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['weight', 'weight_percentage', 'is_weight_locked']);
        });
    }
};
