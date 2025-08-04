<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = User::where('role', 'trainer')
            ->with('assignedWorkouts')
            ->paginate(10);

        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('admin.trainers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'trainer';

        User::create($validated);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer created successfully.');
    }

    public function show(User $trainer)
    {
        $trainer->load(['assignedWorkouts.user', 'assignedWorkouts.workoutExercises.exercise']);
        return view('admin.trainers.show', compact('trainer'));
    }

    public function edit(User $trainer)
    {
        return view('admin.trainers.edit', compact('trainer'));
    }

    public function update(Request $request, User $trainer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($trainer->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $trainer->update($validated);

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer updated successfully.');
    }

    public function destroy(User $trainer)
    {
        $trainer->delete();

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer deleted successfully.');
    }
}
