<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Models\Workout;
use App\Models\Exercise;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => User::where('role', 'member')->count(),
            'active_members' => User::where('role', 'member')->where('is_active', true)->count(),
            'total_trainers' => User::where('role', 'trainer')->count(),
            'active_memberships' => Membership::where('status', 'active')->count(),
            'total_workouts' => Workout::count(),
            'completed_workouts' => Workout::where('status', 'completed')->count(),
            'total_exercises' => Exercise::count(),
            'monthly_revenue' => Membership::where('created_at', '>=', now()->startOfMonth())->sum('price'),
        ];

        $recent_members = User::where('role', 'member')
            ->latest()
            ->take(5)
            ->get();

        $recent_workouts = Workout::with(['user', 'trainer'])
            ->latest()
            ->take(5)
            ->get();

        $membership_stats = Membership::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_members', 'recent_workouts', 'membership_stats'));
    }
}
