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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['strength', 'cardio', 'flexibility', 'balance', 'sports']);
            $table->enum('muscle_group', ['chest', 'back', 'shoulders', 'arms', 'legs', 'core', 'full_body']);
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->string('equipment_needed')->nullable();
            $table->text('instructions')->nullable();
            $table->string('video_url')->nullable();
            $table->string('image_url')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('calories_burned_per_minute')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
