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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Member
            $table->foreignId('trainer_id')->nullable()->constrained('users')->onDelete('set null'); // Trainer
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['cardio', 'strength', 'flexibility', 'sports', 'group_class']);
            $table->dateTime('workout_date');
            $table->integer('duration')->nullable(); // Duration in minutes
            $table->integer('calories_burned')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
