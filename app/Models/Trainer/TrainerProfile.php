<?php

namespace App\Models\Trainer;

use App\Models\User;

class TrainerProfile extends User
{
    protected $table = 'users';
    
    protected static function booted()
    {
        static::addGlobalScope('trainer', function ($builder) {
            $builder->where('role', 'trainer');
        });
    }
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'specialization',
        'experience_years',
        'certification',
        'hourly_rate',
        'bio',
        'profile_image',
    ];
    
    public function workouts()
    {
        return $this->hasMany(Workout::class, 'trainer_id');
    }
    
    public function bookings()
    {
        return $this->hasMany(TrainerBooking::class, 'trainer_id');
    }
    
    public function getAvailableTimeSlotsAttribute()
    {
        // Generate available time slots for booking
        $slots = [];
        $startTime = 6; // 6 AM
        $endTime = 22; // 10 PM
        
        for ($hour = $startTime; $hour < $endTime; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }
        
        return $slots;
    }
}