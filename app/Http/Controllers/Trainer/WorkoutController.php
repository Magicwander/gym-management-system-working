<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Trainer\Workout;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WorkoutController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $trainer = auth()->user();
        $workouts = Workout::where('trainer_id', $trainer->id)
            ->with(['member', 'exercises'])
            ->latest()
            ->paginate(15);
        
        return view('trainer.workouts.index', compact('workouts'));
    }
    
    public function create()
    {
        $trainer = auth()->user();
        $members = User::where('role', 'member')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $exercises = Exercise::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('trainer.workouts.create', compact('members', 'exercises'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workout_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:15|max:180',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'calories_target' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'price_lkr' => 'required|numeric|min:0|max:999999.99',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.weight' => 'nullable|numeric|min:0',
            'exercises.*.rest_time' => 'nullable|integer|min:0',
            'exercises.*.notes' => 'nullable|string',
        ]);
        
        $validated['trainer_id'] = auth()->id();
        $validated['status'] = 'scheduled';
        
        // Map member_id to user_id for database compatibility
        $validated['user_id'] = $validated['member_id'];
        unset($validated['member_id']);
        
        // Map difficulty_level to type and calories_target to calories_burned
        // Map difficulty levels to workout types
        $typeMapping = [
            'beginner' => 'flexibility',
            'intermediate' => 'strength', 
            'advanced' => 'sports'
        ];
        $validated['type'] = $typeMapping[$validated['difficulty_level']] ?? 'strength';
        $validated['calories_burned'] = $validated['calories_target'] ?? 0;
        unset($validated['difficulty_level']);
        unset($validated['calories_target']);
        
        $exercises = $validated['exercises'];
        unset($validated['exercises']);
        
        $workout = Workout::create($validated);
        
        // Attach exercises to workout
        foreach ($exercises as $exerciseData) {
            $workout->exercises()->attach($exerciseData['exercise_id'], [
                'sets' => $exerciseData['sets'],
                'reps' => $exerciseData['reps'],
                'weight_kg' => $exerciseData['weight'] ?? null,
                'rest_seconds' => $exerciseData['rest_time'] ?? null,
                'notes' => $exerciseData['notes'] ?? null,
            ]);
        }
        
        return redirect()->route('trainer.workouts.index')
            ->with('success', 'Workout created successfully.');
    }
    
    public function show(Workout $workout)
    {
        $this->authorize('view', $workout);
        
        $workout->load(['member', 'exercises', 'trainer']);
        return view('trainer.workouts.show', compact('workout'));
    }
    
    public function edit(Workout $workout)
    {
        $this->authorize('update', $workout);
        
        $members = User::where('role', 'member')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $exercises = Exercise::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        $workout->load(['exercises']);
        
        return view('trainer.workouts.edit', compact('workout', 'members', 'exercises'));
    }
    
    public function update(Request $request, Workout $workout)
    {
        $this->authorize('update', $workout);
        
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workout_date' => 'required|date',
            'duration' => 'required|integer|min:15|max:180',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'calories_target' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'price_lkr' => 'required|numeric|min:0|max:999999.99',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.weight' => 'nullable|numeric|min:0',
            'exercises.*.rest_time' => 'nullable|integer|min:0',
            'exercises.*.notes' => 'nullable|string',
        ]);
        
        // Map member_id to user_id for database compatibility
        $validated['user_id'] = $validated['member_id'];
        unset($validated['member_id']);
        
        // Map difficulty_level to type and calories_target to calories_burned
        // Map difficulty levels to workout types
        $typeMapping = [
            'beginner' => 'flexibility',
            'intermediate' => 'strength', 
            'advanced' => 'sports'
        ];
        $validated['type'] = $typeMapping[$validated['difficulty_level']] ?? 'strength';
        $validated['calories_burned'] = $validated['calories_target'] ?? 0;
        unset($validated['difficulty_level']);
        unset($validated['calories_target']);
        
        $exercises = $validated['exercises'];
        unset($validated['exercises']);
        
        $workout->update($validated);
        
        // Sync exercises
        $workout->exercises()->detach();
        foreach ($exercises as $exerciseData) {
            $workout->exercises()->attach($exerciseData['exercise_id'], [
                'sets' => $exerciseData['sets'],
                'reps' => $exerciseData['reps'],
                'weight_kg' => $exerciseData['weight'] ?? null,
                'rest_seconds' => $exerciseData['rest_time'] ?? null,
                'notes' => $exerciseData['notes'] ?? null,
            ]);
        }
        
        return redirect()->route('trainer.workouts.index')
            ->with('success', 'Workout updated successfully.');
    }
    
    public function destroy(Workout $workout)
    {
        $this->authorize('delete', $workout);
        
        $workout->exercises()->detach();
        $workout->delete();
        
        return redirect()->route('trainer.workouts.index')
            ->with('success', 'Workout deleted successfully.');
    }
    
    public function markCompleted(Workout $workout)
    {
        $this->authorize('update', $workout);
        
        $workout->update(['status' => 'completed']);
        
        return back()->with('success', 'Workout marked as completed.');
    }

    public function complete(Workout $workout)
    {
        $this->authorize('update', $workout);
        
        $workout->update(['status' => 'completed']);
        
        return back()->with('success', 'Workout marked as completed.');
    }
    
    public function duplicate(Workout $workout)
    {
        $this->authorize('view', $workout);
        
        $newWorkout = $workout->replicate();
        $newWorkout->name = $workout->name . ' (Copy)';
        $newWorkout->workout_date = now()->addDay();
        $newWorkout->status = 'scheduled';
        $newWorkout->save();
        
        // Copy exercises
        foreach ($workout->exercises as $exercise) {
            $newWorkout->exercises()->attach($exercise->id, [
                'sets' => $exercise->pivot->sets,
                'reps' => $exercise->pivot->reps,
                'weight' => $exercise->pivot->weight,
                'rest_time' => $exercise->pivot->rest_time,
                'notes' => $exercise->pivot->notes,
            ]);
        }
        
        return redirect()->route('trainer.workouts.edit', $newWorkout)
            ->with('success', 'Workout duplicated successfully. You can now modify it.');
    }
}