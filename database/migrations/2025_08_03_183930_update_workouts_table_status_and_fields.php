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
        Schema::table('workouts', function (Blueprint $table) {
            // Drop the existing status column and recreate it with new values
            $table->dropColumn('status');
        });

        Schema::table('workouts', function (Blueprint $table) {
            // Add the new status column with correct values
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');

            // Update type enum to include new workout types
            $table->dropColumn('type');
        });

        Schema::table('workouts', function (Blueprint $table) {
            $table->enum('type', ['cardio', 'strength', 'flexibility', 'sports', 'group_class']);

            // Change workout_date to datetime if it's not already
            $table->dropColumn('workout_date');
        });

        Schema::table('workouts', function (Blueprint $table) {
            $table->dateTime('workout_date');

            // Add duration column if it doesn't exist
            if (!Schema::hasColumn('workouts', 'duration')) {
                $table->integer('duration')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workouts', function (Blueprint $table) {
            // Revert changes
            $table->dropColumn(['status', 'type', 'workout_date', 'duration']);
            $table->enum('status', ['planned', 'in_progress', 'completed', 'skipped'])->default('planned');
            $table->enum('type', ['strength', 'cardio', 'mixed', 'flexibility']);
            $table->date('workout_date');
        });
    }
};
