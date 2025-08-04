<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercise;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::latest()->paginate(10);
        return view('admin.exercises.index', compact('exercises'));
    }

    public function create()
    {
        return view('admin.exercises.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:strength,cardio,flexibility,balance,sports',
            'muscle_group' => 'required|in:chest,back,shoulders,arms,legs,core,full_body',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'equipment_needed' => 'nullable|string',
            'instructions' => 'nullable|string',
            'video_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:1',
            'calories_burned_per_minute' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Exercise::create($validated);

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercise created successfully.');
    }

    public function show(Exercise $exercise)
    {
        return view('admin.exercises.show', compact('exercise'));
    }

    public function edit(Exercise $exercise)
    {
        return view('admin.exercises.edit', compact('exercise'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:strength,cardio,flexibility,balance,sports',
            'muscle_group' => 'required|in:chest,back,shoulders,arms,legs,core,full_body',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'equipment_needed' => 'nullable|string',
            'instructions' => 'nullable|string',
            'video_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'duration_minutes' => 'nullable|integer|min:1',
            'calories_burned_per_minute' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $exercise->update($validated);

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercise updated successfully.');
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return redirect()->route('admin.exercises.index')
            ->with('success', 'Exercise deleted successfully.');
    }
}
