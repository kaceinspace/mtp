<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, clean up invalid team values (set to NULL)
        DB::statement("UPDATE projects SET team = NULL WHERE team NOT REGEXP '^[0-9]+$'");

        Schema::table('projects', function (Blueprint $table) {
            // Change team from string to unsignedBigInteger
            $table->unsignedBigInteger('team')->nullable()->change();

            // Add foreign key constraint
            $table->foreign('team')->references('id')->on('teams')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['team']);

            // Change back to string
            $table->string('team')->nullable()->change();
        });
    }
};
