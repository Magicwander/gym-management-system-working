<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('frontend.home');
})->name('home');





// Customer Registration Route
Route::get('/register-customer', function () {
    return view('auth.register-customer');
})->name('register.customer');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Load role-based routes
require __DIR__.'/admin.php';
require __DIR__.'/trainer.php';
require __DIR__.'/customer.php';

// Debug route to create test users
Route::get('/create-test-users', function () {
    try {
        // Create Admin
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@hermesfitness.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create Trainer
        \App\Models\User::updateOrCreate(
            ['email' => 'trainer@hermesfitness.com'],
            [
                'name' => 'John Trainer',
                'password' => bcrypt('password'),
                'role' => 'trainer',
                'is_active' => true,
            ]
        );

        // Create Test Members
        \App\Models\User::updateOrCreate(
            ['email' => 'alice@example.com'],
            [
                'name' => 'Alice Johnson',
                'password' => bcrypt('password'),
                'role' => 'member',
                'is_active' => true,
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'bob@example.com'],
            [
                'name' => 'Bob Smith',
                'password' => bcrypt('password'),
                'role' => 'member',
                'is_active' => true,
            ]
        );

        // Create sample exercises
        \App\Models\Exercise::updateOrCreate(
            ['name' => 'Push-ups'],
            [
                'description' => 'Classic upper body exercise',
                'category' => 'strength',
                'muscle_groups' => 'Chest, Shoulders, Triceps',
                'equipment' => 'None',
                'difficulty' => 'beginner',
                'duration' => 15,
                'is_active' => true,
            ]
        );

        \App\Models\Exercise::updateOrCreate(
            ['name' => 'Running'],
            [
                'description' => 'Cardiovascular exercise',
                'category' => 'cardio',
                'muscle_groups' => 'Full Body',
                'equipment' => 'Treadmill',
                'difficulty' => 'intermediate',
                'duration' => 30,
                'is_active' => true,
            ]
        );

        return "Test users created successfully!<br>
                Admin: admin@hermesfitness.com / password<br>
                Trainer: trainer@hermesfitness.com / password<br>
                Members: alice@example.com, bob@example.com / password";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage() . " - Line: " . $e->getLine() . " - File: " . $e->getFile();
    }
});

// Test route to check member functionality
Route::get('/test-member-routes', function () {
    $routes = [
        'member.dashboard' => route('member.dashboard'),
        'member.workouts' => route('member.workouts'),
        'member.workouts.book' => route('member.workouts.book'),
        'member.exercises' => route('member.exercises'),
        'member.progress' => route('member.progress'),
    ];

    $html = '<h3>Member Routes Test</h3>';
    foreach ($routes as $name => $url) {
        $html .= "<p><strong>$name:</strong> <a href='$url' target='_blank'>$url</a></p>";
    }

    return $html;
});

require __DIR__.'/auth.php';
