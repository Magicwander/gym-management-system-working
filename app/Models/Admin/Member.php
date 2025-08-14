<?php

namespace App\Models\Admin;

use App\Models\User;
use App\Models\Customer\CustomerBooking;
use App\Models\Customer\PaymentRecord;

class Member extends User
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
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'role',
        'is_active',
        'emergency_contact',
        'emergency_phone',
        'medical_conditions',
        'fitness_goals',
    ];
    
    public function bookings()
    {
        return $this->hasMany(CustomerBooking::class, 'member_id');
    }
    
    public function payments()
    {
        return $this->hasMany(PaymentRecord::class, 'member_id');
    }
    
    public function getTotalBookingsAttribute()
    {
        return $this->bookings()->count();
    }
    
    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }
}