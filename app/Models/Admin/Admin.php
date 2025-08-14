<?php

namespace App\Models\Admin;

use App\Models\User;

class Admin extends User
{
    protected $table = 'users';
    
    protected static function booted()
    {
        static::addGlobalScope('admin', function ($builder) {
            $builder->where('role', 'admin');
        });
    }
    
    public function getTotalMembersAttribute()
    {
        return User::where('role', 'member')->count();
    }
    
    public function getActiveMembersAttribute()
    {
        return User::where('role', 'member')->where('is_active', true)->count();
    }
    
    public function getTotalTrainersAttribute()
    {
        return User::where('role', 'trainer')->count();
    }
    
    public function getMonthlyRevenueAttribute()
    {
        return \App\Models\Customer\PaymentRecord::where('created_at', '>=', now()->startOfMonth())
            ->where('status', 'completed')
            ->sum('amount');
    }
}