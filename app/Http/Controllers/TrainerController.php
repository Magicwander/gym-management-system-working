<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Support\Facades\Auth;
class TrainerController extends Controller
{
    /**
     * Show trainer dashboard
     */
    public function dashboard()
    {
        $trainer = Auth::user();

        // Get trainer's statistics
        $totalClients = User::where('trainer_id', $trainer->id)->count();
        $totalWorkouts = Workout::where('trainer_id', $trainer->id)->count();
        $todayWorkouts = Workout::where('trainer_id', $trainer->id)
            ->whereDate('workout_date', today())
            ->count();
        $upcomingWorkouts = Workout::where('trainer_id', $trainer->id)
            ->where('workout_date', '>', now())
            ->where('status', 'scheduled')
            ->take(5)
            ->get();

        return view('trainer.dashboard', compact(
            'trainer', 'totalClients', 'totalWorkouts', 'todayWorkouts', 'upcomingWorkouts'
        ));
    }

    /**
     * Show trainer's clients
     */
    public function clients()
    {
        $trainer = Auth::user();
        $clients = $trainer->assignedWorkouts()
            ->with(['user.memberships'])
            ->get()
            ->pluck('user')
            ->unique('id')
            ->values();

        return view('trainer.clients', compact('clients', 'trainer'));
    }

    /**
     * Show trainer's workouts
     */
    public function workouts()
    {
        $trainer = Auth::user();
        $workouts = $trainer->assignedWorkouts()
            ->with(['user', 'workoutExercises.exercise'])
            ->orderBy('workout_date', 'desc')
            ->paginate(15);

        return view('trainer.workouts', compact('workouts', 'trainer'));
    }

    /**
     * Show create workout form
     */
    public function createWorkout()
    {
        $trainer = Auth::user();
        $clients = $trainer->assignedWorkouts()
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id')
            ->values();
        
        $exercises = Exercise::where('is_active', true)->get();

        return view('trainer.create-workout', compact('clients', 'exercises', 'trainer'));
    }

    /**
     * Store new workout
     */
    public function storeWorkout(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:strength,cardio,flexibility,mixed',
            'workout_date' => 'required|date',
            'total_duration_minutes' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'exercises' => 'nullable|array',
            'exercises.*.exercise_id' => 'required_with:exercises|exists:exercises,id',
            'exercises.*.sets' => 'required_with:exercises|integer|min:1',
            'exercises.*.reps' => 'required_with:exercises|integer|min:1',
            'exercises.*.weight' => 'nullable|numeric|min:0',
            'exercises.*.duration_minutes' => 'nullable|integer|min:1',
            'exercises.*.rest_seconds' => 'nullable|integer|min:0',
        ]);

        $workout = Workout::create([
            'name' => $validated['name'],
            'user_id' => $validated['user_id'],
            'trainer_id' => Auth::id(),
            'type' => $validated['type'],
            'workout_date' => $validated['workout_date'],
            'total_duration_minutes' => $validated['total_duration_minutes'],
            'description' => $validated['description'],
            'status' => 'scheduled',
        ]);

        // Add exercises if provided
        if (isset($validated['exercises'])) {
            foreach ($validated['exercises'] as $exerciseData) {
                $workout->workoutExercises()->create([
                    'exercise_id' => $exerciseData['exercise_id'],
                    'sets' => $exerciseData['sets'],
                    'reps' => $exerciseData['reps'],
                    'weight' => $exerciseData['weight'] ?? null,
                    'duration_minutes' => $exerciseData['duration_minutes'] ?? null,
                    'rest_seconds' => $exerciseData['rest_seconds'] ?? 60,
                ]);
            }
        }

        return redirect()->route('trainer.workouts')
            ->with('success', 'Workout created successfully!');
    }

    /**
     * Show trainer's schedule
     */
    public function schedule()
    {
        $trainer = Auth::user();
        $workouts = $trainer->assignedWorkouts()
            ->with(['user'])
            ->where('workout_date', '>=', now()->startOfWeek())
            ->where('workout_date', '<=', now()->endOfWeek()->addWeeks(2))
            ->orderBy('workout_date')
            ->get();

        return view('trainer.schedule', compact('workouts', 'trainer'));
    }

    /**
     * Show client progress
     */
    public function clientProgress($clientId = null)
    {
        $trainer = Auth::user();
        $clients = $trainer->assignedWorkouts()
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id')
            ->values();

        $selectedClient = null;
        $clientWorkouts = collect();

        if ($clientId) {
            $selectedClient = $clients->where('id', $clientId)->first();
            if ($selectedClient) {
                $clientWorkouts = $trainer->assignedWorkouts()
                    ->where('user_id', $clientId)
                    ->with(['workoutExercises.exercise'])
                    ->orderBy('workout_date', 'desc')
                    ->get();
            }
        }

        return view('trainer.client-progress', compact('clients', 'selectedClient', 'clientWorkouts', 'trainer'));
    }
}
