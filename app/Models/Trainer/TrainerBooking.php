<?php

namespace App\Models\Trainer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class TrainerBooking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'trainer_id',
        'member_id',
        'booking_date',
        'start_time',
        'end_time',
        'session_type',
        'status',
        'notes',
        'price',
    ];
    
    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
    ];
    
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
    
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
    
    public function payment()
    {
        return $this->hasOne(\App\Models\Customer\PaymentRecord::class, 'booking_id');
    }
    
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', today());
    }
    
    public function getFormattedTimeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }
}