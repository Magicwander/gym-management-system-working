<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Payment extends Model
{
    use HasFactory;
    
    protected $table = 'payment_records';
    
    protected $fillable = [
        'member_id',
        'booking_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'payment_date',
        'description',
    ];
    
    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];
    
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
    
    public function booking()
    {
        return $this->belongsTo(\App\Models\Customer\CustomerBooking::class, 'booking_id');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeThisMonth($query)
    {
        return $query->where('created_at', '>=', now()->startOfMonth());
    }
}