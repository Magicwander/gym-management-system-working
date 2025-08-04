<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    protected $fillable = [
        'workout_id',
        'exercise_id',
        'sets',
        'reps',
        'weight_kg',
        'duration_seconds',
        'distance_km',
        'rest_seconds',
        'notes',
        'completed',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'completed' => 'boolean',
    ];

    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
