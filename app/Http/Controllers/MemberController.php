<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Show member dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $recentWorkouts = $user->workouts()->with('trainer')->latest()->take(5)->get();
        $activeMembership = $user->memberships()->where('status', 'active')->first();
        
        return view('member.dashboard', compact('recentWorkouts', 'activeMembership'));
    }

    /**
     * Show workout booking form
     */
    public function bookWorkout()
    {
        $trainers = User::where('role', 'trainer')->where('is_active', true)->get();
        $exercises = Exercise::where('is_active', true)->get();
        
        return view('member.book-workout', compact('trainers', 'exercises'));
    }

    /**
     * Store workout booking
     */
    public function storeWorkout(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cardio,strength,flexibility,sports,group_class',
            'workout_date' => 'required|date|after_or_equal:today',
            'workout_time' => 'required|date_format:H:i',
            'trainer_id' => 'nullable|exists:users,id',
            'exercises' => 'nullable|array',
            'exercises.*' => 'exists:exercises,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $workoutDateTime = Carbon::parse($request->workout_date . ' ' . $request->workout_time);

        $workout = Workout::create([
            'user_id' => Auth::id(),
            'trainer_id' => $request->trainer_id,
            'name' => $request->name,
            'type' => $request->type,
            'workout_date' => $workoutDateTime,
            'duration' => 60, // Default 60 minutes
            'status' => 'scheduled',
            'notes' => $request->notes,
        ]);

        // Attach exercises if selected
        if ($request->exercises) {
            $workout->exercises()->attach($request->exercises);
        }

        return redirect()->route('member.dashboard')
            ->with('success', 'Workout booked successfully!');
    }

    /**
     * Show member's workouts
     */
    public function workouts()
    {
        $workouts = Auth::user()->workouts()
            ->with(['trainer', 'exercises'])
            ->orderBy('workout_date', 'desc')
            ->paginate(10);

        return view('member.workouts', compact('workouts'));
    }

    /**
     * Show workout details
     */
    public function showWorkout(Workout $workout)
    {
        // Ensure user can only view their own workouts
        if ($workout->user_id !== Auth::id()) {
            abort(403);
        }

        $workout->load(['trainer', 'exercises']);
        
        return view('member.workout-details', compact('workout'));
    }

    /**
     * Cancel workout
     */
    public function cancelWorkout(Workout $workout)
    {
        // Ensure user can only cancel their own workouts
        if ($workout->user_id !== Auth::id()) {
            abort(403);
        }

        // Can only cancel scheduled workouts
        if ($workout->status !== 'scheduled') {
            return back()->with('error', 'Only scheduled workouts can be cancelled.');
        }

        // Can only cancel workouts that are at least 2 hours away
        if ($workout->workout_date->diffInHours(now()) < 2) {
            return back()->with('error', 'Workouts can only be cancelled at least 2 hours in advance.');
        }

        $workout->update(['status' => 'cancelled']);

        return back()->with('success', 'Workout cancelled successfully.');
    }

    /**
     * Browse exercises
     */
    public function browseExercises()
    {
        $exercises = Exercise::where('is_active', true)
            ->paginate(12);

        $categories = Exercise::select('category')
            ->where('is_active', true)
            ->distinct()
            ->pluck('category');

        return view('member.exercises', compact('exercises', 'categories'));
    }

    /**
     * Show exercise details
     */
    public function showExercise(Exercise $exercise)
    {
        return view('member.exercise-details', compact('exercise'));
    }

    /**
     * Show member progress
     */
    public function progress()
    {
        $user = Auth::user();
        
        // Get workout statistics
        $totalWorkouts = $user->workouts()->count();
        $completedWorkouts = $user->workouts()->where('status', 'completed')->count();
        $thisMonthWorkouts = $user->workouts()
            ->where('workout_date', '>=', now()->startOfMonth())
            ->count();
        
        // Get monthly workout data for chart
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = $user->workouts()
                ->whereYear('workout_date', $month->year)
                ->whereMonth('workout_date', $month->month)
                ->count();
            
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // Get workout type distribution
        $workoutTypes = $user->workouts()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Get recent achievements
        $achievements = $this->calculateAchievements($user);

        return view('member.progress', compact(
            'totalWorkouts', 
            'completedWorkouts', 
            'thisMonthWorkouts',
            'monthlyData',
            'workoutTypes',
            'achievements'
        ));
    }

    /**
     * Calculate member achievements
     */
    private function calculateAchievements($user)
    {
        $achievements = [];
        $completedWorkouts = $user->workouts()->where('status', 'completed')->count();
        $totalCalories = $user->workouts()->sum('calories_burned');

        if ($completedWorkouts >= 1) {
            $achievements[] = ['name' => 'First Workout', 'icon' => '🏃', 'color' => 'warning'];
        }
        
        if ($completedWorkouts >= 5) {
            $achievements[] = ['name' => '5 Workouts Complete', 'icon' => '💪', 'color' => 'success'];
        }
        
        if ($completedWorkouts >= 10) {
            $achievements[] = ['name' => 'Fitness Enthusiast', 'icon' => '🔥', 'color' => 'primary'];
        }
        
        if ($completedWorkouts >= 25) {
            $achievements[] = ['name' => 'Workout Warrior', 'icon' => '⚡', 'color' => 'danger'];
        }
        
        if ($totalCalories >= 1000) {
            $achievements[] = ['name' => 'Calorie Crusher', 'icon' => '🔥', 'color' => 'info'];
        }

        // Check for consistency (workouts in last 7 days)
        $recentWorkouts = $user->workouts()
            ->where('workout_date', '>=', now()->subDays(7))
            ->where('status', 'completed')
            ->count();
            
        if ($recentWorkouts >= 3) {
            $achievements[] = ['name' => 'Consistency King', 'icon' => '👑', 'color' => 'dark'];
        }

        return $achievements;
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string|max:1000',
            'fitness_goals' => 'nullable|string|max:1000'
        ]);

        $user->update($request->only([
            'name', 'phone', 'date_of_birth', 'gender', 'address',
            'emergency_contact', 'emergency_phone', 'medical_conditions', 'fitness_goals'
        ]));

        return back()->with('success', 'Profile updated successfully!');
    }
}
