<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin\Member;
use App\Models\Admin\Trainer;
use App\Models\Admin\Payment;
use App\Models\Customer\CustomerBooking;
use App\Models\Customer\PaymentRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::where('is_active', true)->count(),
            'total_trainers' => Trainer::count(),
            'active_trainers' => Trainer::where('is_active', true)->count(),
            'total_bookings' => CustomerBooking::count(),
            'confirmed_bookings' => CustomerBooking::confirmed()->count(),
            'monthly_revenue' => PaymentRecord::thisMonth()->completed()->sum('amount'),
            'today_revenue' => PaymentRecord::today()->completed()->sum('amount'),
        ];
        
        $recentMembers = Member::latest()->take(5)->get();
        $recentBookings = CustomerBooking::with(['member', 'trainer'])
            ->latest()
            ->take(5)
            ->get();
        
        $recentPayments = PaymentRecord::with('member')
            ->completed()
            ->latest()
            ->take(5)
            ->get();
        
        $monthlyStats = PaymentRecord::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('admin.dashboard', compact(
            'stats',
            'recentMembers',
            'recentBookings',
            'recentPayments',
            'monthlyStats'
        ));
    }
}
