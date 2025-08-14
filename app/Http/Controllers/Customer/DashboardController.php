<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\MemberProfile;
use App\Models\Customer\CustomerBooking;
use App\Models\Customer\PaymentRecord;
use App\Models\Trainer\Workout;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $member = auth()->user();
        
        $stats = [
            'total_bookings' => CustomerBooking::where('member_id', $member->id)->count(),
            'confirmed_bookings' => CustomerBooking::where('member_id', $member->id)->confirmed()->count(),
            'upcoming_bookings' => CustomerBooking::where('member_id', $member->id)->upcoming()->confirmed()->count(),
            'completed_bookings' => Workout::where('member_id', $member->id)->completed()->count(),
            'total_workouts' => Workout::where('member_id', $member->id)->count(),
            'completed_workouts' => Workout::where('member_id', $member->id)->completed()->count(),
            'total_spent' => PaymentRecord::where('member_id', $member->id)->completed()->sum('amount'),
        ];
        
        $upcomingBookings = CustomerBooking::where('member_id', $member->id)
            ->with('trainer')
            ->upcoming()
            ->confirmed()
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(3)
            ->get();
        
        $recentBookings = CustomerBooking::where('member_id', $member->id)
            ->with('trainer')
            ->latest()
            ->take(5)
            ->get();
        
        $recentWorkouts = Workout::where('member_id', $member->id)
            ->with('trainer')
            ->latest()
            ->take(5)
            ->get();
        
        $recentPayments = PaymentRecord::where('member_id', $member->id)
            ->with('booking.trainer')
            ->latest()
            ->take(5)
            ->get();
        
        $availableTrainers = \App\Models\User::where('role', 'trainer')
            ->where('is_active', true)
            ->take(5)
            ->get();
        
        return view('customer.dashboard', compact(
            'stats',
            'upcomingBookings',
            'recentBookings',
            'recentWorkouts',
            'recentPayments',
            'availableTrainers'
        ));
    }
}