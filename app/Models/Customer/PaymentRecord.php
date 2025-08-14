<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class PaymentRecord extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'member_id',
        'booking_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'payment_date',
        'description',
        'card_last_four',
        'card_type',
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
        return $this->belongsTo(CustomerBooking::class, 'booking_id');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
    public function scopeThisMonth($query)
    {
        return $query->where('created_at', '>=', now()->startOfMonth());
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2);
    }
    
    public static function generateTransactionId()
    {
        return 'TXN_' . strtoupper(uniqid()) . '_' . time();
    }
}