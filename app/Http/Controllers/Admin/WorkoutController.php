<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Workout;
use App\Models\User;
use App\Models\Exercise;

class WorkoutController extends Controller
{
    public function index()
    {
        $workouts = Workout::with(['user', 'trainer'])
            ->latest()
            ->paginate(10);

        return view('admin.workouts.index', compact('workouts'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        $trainers = User::where('role', 'trainer')->where('is_active', true)->get();
        $exercises = Exercise::where('is_active', true)->get();

        return view('admin.workouts.create', compact('members', 'trainers', 'exercises'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'trainer_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:strength,cardio,mixed,flexibility',
            'workout_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'total_duration_minutes' => 'nullable|integer|min:1',
            'calories_burned' => 'nullable|integer|min:0',
            'status' => 'required|in:planned,in_progress,completed,skipped',
            'notes' => 'nullable|string',
        ]);

        Workout::create($validated);

        return redirect()->route('admin.workouts.index')
            ->with('success', 'Workout created successfully.');
    }

    public function show(Workout $workout)
    {
        $workout->load(['user', 'trainer', 'workoutExercises.exercise']);
        return view('admin.workouts.show', compact('workout'));
    }

    public function edit(Workout $workout)
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        $trainers = User::where('role', 'trainer')->where('is_active', true)->get();
        $exercises = Exercise::where('is_active', true)->get();

        return view('admin.workouts.edit', compact('workout', 'members', 'trainers', 'exercises'));
    }

    public function update(Request $request, Workout $workout)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'trainer_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:strength,cardio,mixed,flexibility',
            'workout_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'total_duration_minutes' => 'nullable|integer|min:1',
            'calories_burned' => 'nullable|integer|min:0',
            'status' => 'required|in:planned,in_progress,completed,skipped',
            'notes' => 'nullable|string',
        ]);

        $workout->update($validated);

        return redirect()->route('admin.workouts.index')
            ->with('success', 'Workout updated successfully.');
    }

    public function destroy(Workout $workout)
    {
        $workout->delete();

        return redirect()->route('admin.workouts.index')
            ->with('success', 'Workout deleted successfully.');
    }
}
