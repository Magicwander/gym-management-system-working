<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer\TrainerProfile;
use App\Models\Trainer\TrainerBooking;
use App\Models\Trainer\Workout;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $trainer = auth()->user();
        
        $stats = [
            'total_bookings' => TrainerBooking::where('trainer_id', $trainer->id)->count(),
            'confirmed_bookings' => TrainerBooking::where('trainer_id', $trainer->id)->confirmed()->count(),
            'today_bookings' => TrainerBooking::where('trainer_id', $trainer->id)->today()->count(),
            'upcoming_bookings' => TrainerBooking::where('trainer_id', $trainer->id)->upcoming()->count(),
            'total_workouts' => Workout::where('trainer_id', $trainer->id)->count(),
            'active_clients' => TrainerBooking::where('trainer_id', $trainer->id)
                ->confirmed()
                ->distinct('member_id')
                ->count(),
        ];
        
        $todayBookings = TrainerBooking::where('trainer_id', $trainer->id)
            ->with('member')
            ->today()
            ->confirmed()
            ->orderBy('start_time')
            ->get();
        
        $upcomingBookings = TrainerBooking::where('trainer_id', $trainer->id)
            ->with('member')
            ->upcoming()
            ->confirmed()
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
        
        $recentWorkouts = Workout::where('trainer_id', $trainer->id)
            ->with('member')
            ->latest()
            ->take(5)
            ->get();
        
        return view('trainer.dashboard', compact(
            'stats',
            'todayBookings',
            'upcomingBookings',
            'recentWorkouts'
        ));
    }
}