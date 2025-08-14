<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\CustomerBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $member = auth()->user();
        $bookings = CustomerBooking::where('member_id', $member->id)
            ->with(['trainer', 'payment'])
            ->latest()
            ->paginate(15);
        
        return view('customer.bookings.index', compact('bookings'));
    }
    
    public function create()
    {
        $trainers = User::where('role', 'trainer')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('customer.bookings.create', compact('trainers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trainer_id' => 'required|exists:users,id',
            'booking_date' => 'required|date|after:today',
            'session_type' => 'required|in:personal_training,group_session,consultation',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $trainer = User::findOrFail($validated['trainer_id']);
        
        // Auto-assign time slot
        $availableTime = CustomerBooking::getAvailableTimeSlot(
            $validated['trainer_id'],
            $validated['booking_date']
        );
        
        $startTime = Carbon::parse($validated['booking_date'] . ' ' . $availableTime);
        $endTime = $startTime->copy()->addHour(); // 1-hour sessions
        
        // Set price based on session type
        $prices = [
            'personal_training' => 75.00,
            'group_session' => 45.00,
            'consultation' => 50.00,
        ];
        
        $booking = CustomerBooking::create([
            'member_id' => auth()->id(),
            'trainer_id' => $validated['trainer_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'session_type' => $validated['session_type'],
            'status' => 'pending',
            'notes' => $validated['notes'],
            'price' => $prices[$validated['session_type']],
        ]);
        
        return redirect()->route('customer.bookings.payment', $booking)
            ->with('success', 'Booking created successfully. Please complete payment to confirm.');
    }
    
    public function show(CustomerBooking $booking)
    {
        $this->authorize('view', $booking);
        
        $booking->load(['trainer', 'payment']);
        return view('customer.bookings.show', compact('booking'));
    }
    
    public function payment(CustomerBooking $booking)
    {
        $this->authorize('view', $booking);
        
        if ($booking->status === 'confirmed') {
            return redirect()->route('customer.bookings.show', $booking)
                ->with('info', 'This booking is already confirmed.');
        }
        
        return view('customer.bookings.payment', compact('booking'));
    }
    
    public function cancel(CustomerBooking $booking)
    {
        $this->authorize('update', $booking);
        
        if ($booking->booking_date <= now()->addDay()) {
            return back()->with('error', 'Cannot cancel bookings less than 24 hours in advance.');
        }
        
        $booking->update(['status' => 'cancelled']);
        
        // If payment exists, mark it as refunded (in a real app, process actual refund)
        if ($booking->payment && $booking->payment->status === 'completed') {
            $booking->payment->update(['status' => 'refunded']);
        }
        
        return redirect()->route('customer.bookings.index')
            ->with('success', 'Booking cancelled successfully.');
    }
    
    public function reschedule(Request $request, CustomerBooking $booking)
    {
        $this->authorize('update', $booking);
        
        if ($booking->booking_date <= now()->addDay()) {
            return back()->with('error', 'Cannot reschedule bookings less than 24 hours in advance.');
        }
        
        $validated = $request->validate([
            'booking_date' => 'required|date|after:today',
        ]);
        
        // Auto-assign new time slot
        $availableTime = CustomerBooking::getAvailableTimeSlot(
            $booking->trainer_id,
            $validated['booking_date']
        );
        
        $startTime = Carbon::parse($validated['booking_date'] . ' ' . $availableTime);
        $endTime = $startTime->copy()->addHour();
        
        $booking->update([
            'booking_date' => $validated['booking_date'],
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);
        
        return back()->with('success', 'Booking rescheduled successfully.');
    }
    
    public function history()
    {
        $member = auth()->user();
        $bookings = CustomerBooking::where('member_id', $member->id)
            ->with(['trainer', 'payment'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->latest()
            ->paginate(15);
        
        return view('customer.bookings.history', compact('bookings'));
    }
    
    public function upcoming()
    {
        $member = auth()->user();
        $bookings = CustomerBooking::where('member_id', $member->id)
            ->with(['trainer', 'payment'])
            ->upcoming()
            ->confirmed()
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->paginate(15);
        
        return view('customer.bookings.upcoming', compact('bookings'));
    }
}