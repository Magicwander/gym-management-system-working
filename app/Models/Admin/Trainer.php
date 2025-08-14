<?php

namespace App\Models\Admin;

use App\Models\User;
use App\Models\Trainer\TrainerBooking;

class Trainer extends User
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
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'role',
        'is_active',
        'specialization',
        'experience_years',
        'certification',
        'hourly_rate',
    ];
    
    public function bookings()
    {
        return $this->hasMany(TrainerBooking::class, 'trainer_id');
    }
    
    public function getActiveBookingsAttribute()
    {
        return $this->bookings()->where('status', 'confirmed')->count();
    }
}