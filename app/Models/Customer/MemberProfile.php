<?php

namespace App\Models\Customer;

use App\Models\User;

class MemberProfile extends User
{
    protected $table = 'users';
    
    protected static function booted()
    {
        static::addGlobalScope('member', function ($builder) {
            $builder->where('role', 'member');
        });
    }
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'emergency_contact',
        'emergency_phone',
        'medical_conditions',
        'fitness_goals',
        'profile_image',
    ];
    
    public function bookings()
    {
        return $this->hasMany(CustomerBooking::class, 'member_id');
    }
    
    public function payments()
    {
        return $this->hasMany(PaymentRecord::class, 'member_id');
    }
    
    public function workouts()
    {
        return $this->hasMany(\App\Models\Trainer\Workout::class, 'member_id');
    }
    
    public function getUpcomingBookingsAttribute()
    {
        return $this->bookings()->upcoming()->confirmed()->get();
    }
    
    public function getTotalSpentAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }
}