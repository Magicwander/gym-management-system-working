<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer\TrainerBooking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $trainer = auth()->user();
        $query = TrainerBooking::where('trainer_id', $trainer->id)->with('member');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }
        
        $bookings = $query->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);
        
        return view('trainer.bookings.index', compact('bookings'));
    }
    
    public function show(TrainerBooking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['member', 'payment']);
        return view('trainer.bookings.show', compact('booking'));
    }
    
    public function calendar(Request $request)
    {
        $trainer = auth()->user();
        $date = $request->input('date', now()->toDateString());
        $currentDate = Carbon::parse($date);
        
        // Get bookings for the current week
        $startOfWeek = $currentDate->copy()->startOfWeek();
        $endOfWeek = $currentDate->copy()->endOfWeek();
        
        $bookings = TrainerBooking::where('trainer_id', $trainer->id)
            ->with('member')
            ->whereBetween('booking_date', [$startOfWeek, $endOfWeek])
            ->confirmed()
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();
        
        // Group bookings by date
        $bookingsByDate = $bookings->groupBy(function ($booking) {
            return $booking->booking_date->format('Y-m-d');
        });
        
        // Generate week days
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'date' => $day->format('Y-m-d'),
                'day_name' => $day->format('l'),
                'day_number' => $day->format('j'),
                'is_today' => $day->isToday(),
                'bookings' => $bookingsByDate->get($day->format('Y-m-d'), collect())
            ];
        }
        
        return view('trainer.bookings.calendar', compact('weekDays', 'currentDate'));
    }
    
    public function dayView(Request $request)
    {
        $trainer = auth()->user();
        $date = $request->input('date', now()->toDateString());
        $currentDate = Carbon::parse($date);
        
        $bookings = TrainerBooking::where('trainer_id', $trainer->id)
            ->with('member')
            ->whereDate('booking_date', $date)
            ->confirmed()
            ->orderBy('start_time')
            ->get();
        
        // Generate time slots (6 AM to 10 PM)
        $timeSlots = [];
        for ($hour = 6; $hour < 22; $hour++) {
            $time = sprintf('%02d:00', $hour);
            $timeSlots[] = [
                'time' => $time,
                'display_time' => Carbon::createFromFormat('H:i', $time)->format('g:i A'),
                'booking' => $bookings->first(function ($booking) use ($time) {
                    return $booking->start_time->format('H:i') === $time;
                })
            ];
        }
        
        return view('trainer.bookings.day-view', compact('timeSlots', 'currentDate', 'bookings'));
    }
    
    public function updateStatus(Request $request, TrainerBooking $booking)
    {
        $this->authorize('update', $booking);
        
        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed',
            'notes' => 'nullable|string'
        ]);
        
        $booking->update($validated);
        
        $statusMessage = [
            'confirmed' => 'Booking confirmed successfully.',
            'cancelled' => 'Booking cancelled successfully.',
            'completed' => 'Booking marked as completed.'
        ];
        
        return back()->with('success', $statusMessage[$validated['status']]);
    }
    
    public function addNotes(Request $request, TrainerBooking $booking)
    {
        $this->authorize('update', $booking);
        
        $validated = $request->validate([
            'notes' => 'required|string|max:1000'
        ]);
        
        $booking->update($validated);
        
        return back()->with('success', 'Notes added successfully.');
    }
    
    public function notifications()
    {
        $trainer = auth()->user();
        
        // Get new bookings (created in last 24 hours)
        $newBookings = TrainerBooking::where('trainer_id', $trainer->id)
            ->where('created_at', '>=', now()->subDay())
            ->with('member')
            ->latest()
            ->get();
        
        // Get today's bookings
        $todayBookings = TrainerBooking::where('trainer_id', $trainer->id)
            ->today()
            ->confirmed()
            ->with('member')
            ->orderBy('start_time')
            ->get();
        
        // Get upcoming bookings (next 3 days)
        $upcomingBookings = TrainerBooking::where('trainer_id', $trainer->id)
            ->whereBetween('booking_date', [now()->addDay(), now()->addDays(3)])
            ->confirmed()
            ->with('member')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();
        
        return view('trainer.bookings.notifications', compact(
            'newBookings',
            'todayBookings',
            'upcomingBookings'
        ));
    }
}