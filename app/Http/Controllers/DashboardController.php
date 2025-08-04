<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Membership;
use App\Models\Workout;
use App\Models\Exercise;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Common data for all roles
        $data = [
            'user' => $user,
            'role' => $user->role
        ];
        
        // Role-specific data
        switch ($user->role) {
            case 'admin':
                $data = array_merge($data, $this->getAdminData());
                break;
            case 'trainer':
                $data = array_merge($data, $this->getTrainerData($user));
                break;
            case 'member':
                $data = array_merge($data, $this->getMemberData($user));
                break;
        }
        
        return view('dashboard', $data);
    }
    
    private function getAdminData()
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
            
        return [
            'stats' => $stats,
            'recent_members' => $recent_members,
            'recent_workouts' => $recent_workouts,
            'membership_stats' => $membership_stats
        ];
    }
    
    private function getTrainerData($user)
    {
        $assigned_workouts = $user->assignedWorkouts()->with(['user', 'workoutExercises.exercise'])->get();
        
        $stats = [
            'assigned_workouts' => $assigned_workouts->count(),
            'active_clients' => $assigned_workouts->pluck('user_id')->unique()->count(),
            'this_week_workouts' => $assigned_workouts->where('workout_date', '>=', now()->startOfWeek())->count(),
            'completed_workouts' => $assigned_workouts->where('status', 'completed')->count(),
        ];
        
        $recent_workouts = $assigned_workouts->take(5);
        $recent_clients = $assigned_workouts->pluck('user')->unique('id')->take(3);
        
        return [
            'stats' => $stats,
            'recent_workouts' => $recent_workouts,
            'recent_clients' => $recent_clients
        ];
    }
    
    private function getMemberData($user)
    {
        $workouts = $user->workouts()->with('trainer')->get();
        $active_membership = $user->activeMembership();
        
        $stats = [
            'total_workouts' => $workouts->count(),
            'completed_workouts' => $workouts->where('status', 'completed')->count(),
            'this_month_workouts' => $workouts->where('workout_date', '>=', now()->startOfMonth())->count(),
            'total_calories' => $workouts->sum('calories_burned') ?: 0,
        ];
        
        $recent_workouts = $workouts->take(5);
        
        return [
            'stats' => $stats,
            'recent_workouts' => $recent_workouts,
            'active_membership' => $active_membership
        ];
    }
}
