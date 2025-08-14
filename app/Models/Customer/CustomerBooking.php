<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class CustomerBooking extends Model
{
    use HasFactory;
    
    protected $table = 'trainer_bookings';
    
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
        return $this->hasOne(PaymentRecord::class, 'booking_id');
    }
    
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', today());
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function getFormattedDateTimeAttribute()
    {
        return $this->booking_date->format('M d, Y') . ' at ' . $this->start_time->format('H:i');
    }
    
    public static function getAvailableTimeSlot($trainerId, $date)
    {
        $existingBookings = self::where('trainer_id', $trainerId)
            ->whereDate('booking_date', $date)
            ->pluck('start_time')
            ->map(function ($time) {
                return \Carbon\Carbon::parse($time)->format('H:i');
            })
            ->toArray();
        
        $availableSlots = [];
        for ($hour = 6; $hour < 22; $hour++) {
            $timeSlot = sprintf('%02d:00', $hour);
            if (!in_array($timeSlot, $existingBookings)) {
                $availableSlots[] = $timeSlot;
            }
        }
        
        return $availableSlots[0] ?? '09:00'; // Default to 9 AM if no slots available
    }
}