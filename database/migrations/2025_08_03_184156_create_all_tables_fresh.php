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
        // Add role column to users table if it doesn't exist
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'trainer', 'member'])->default('member');
                $table->string('phone')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->enum('gender', ['male', 'female', 'other'])->nullable();
                $table->text('address')->nullable();
                $table->string('emergency_contact')->nullable();
                $table->string('emergency_phone')->nullable();
                $table->text('medical_conditions')->nullable();
                $table->text('fitness_goals')->nullable();
                $table->boolean('is_active')->default(true);
                $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('set null');
            });
        }

        // Create memberships table
        if (!Schema::hasTable('memberships')) {
            Schema::create('memberships', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('type', ['basic', 'premium', 'vip']);
                $table->decimal('price', 8, 2);
                $table->date('start_date');
                $table->date('end_date');
                $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
                $table->timestamps();
            });
        }

        // Create exercises table
        if (!Schema::hasTable('exercises')) {
            Schema::create('exercises', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('category');
                $table->string('muscle_groups')->nullable();
                $table->string('equipment')->nullable();
                $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->nullable();
                $table->integer('duration')->nullable();
                $table->string('image')->nullable();
                $table->text('instructions')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create workouts table with correct schema
        if (!Schema::hasTable('workouts')) {
            Schema::create('workouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('type', ['cardio', 'strength', 'flexibility', 'sports', 'group_class']);
                $table->dateTime('workout_date');
                $table->integer('duration')->nullable();
                $table->integer('calories_burned')->nullable();
                $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Create workout_exercises pivot table
        if (!Schema::hasTable('workout_exercises')) {
            Schema::create('workout_exercises', function (Blueprint $table) {
                $table->id();
                $table->foreignId('workout_id')->constrained()->onDelete('cascade');
                $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
                $table->integer('sets')->nullable();
                $table->integer('reps')->nullable();
                $table->decimal('weight', 5, 2)->nullable();
                $table->integer('duration_minutes')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
        Schema::dropIfExists('workouts');
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('memberships');

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn([
                    'role', 'phone', 'date_of_birth', 'gender', 'address',
                    'emergency_contact', 'emergency_phone', 'medical_conditions',
                    'fitness_goals', 'is_active', 'trainer_id'
                ]);
            });
        }
    }
};
