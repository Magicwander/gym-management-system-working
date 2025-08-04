<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'muscle_group',
        'difficulty_level',
        'equipment_needed',
        'instructions',
        'video_url',
        'image_url',
        'duration_minutes',
        'calories_burned_per_minute',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
