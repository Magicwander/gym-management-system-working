<?php

namespace App\Models\Trainer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Exercise;

class Workout extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'trainer_id',
        'member_id',
        'name',
        'description',
        'workout_date',
        'duration',
        'difficulty_level',
        'calories_target',
        'status',
        'notes',
    ];
    
    protected $casts = [
        'workout_date' => 'datetime',
        'duration' => 'integer',
        'calories_target' => 'integer',
    ];
    
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
    
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
    
    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'workout_exercises')
                    ->withPivot('sets', 'reps', 'weight', 'rest_time', 'notes')
                    ->withTimestamps();
    }
    
    public function workoutExercises()
    {
        return $this->hasMany(\App\Models\WorkoutExercise::class);
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('workout_date', '>=', now());
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('workout_date', today());
    }
}