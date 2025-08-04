<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\WorkoutController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/', function () {
    return view('frontend.home');
})->name('home');





// Enrollment Routes
Route::get('/enroll', [EnrollmentController::class, 'create'])->name('enrollment.create');
Route::post('/enroll', [\App\Http\Controllers\EnrollmentController::class, 'store'])->name('enrollment.store');
Route::get('/enrollment/payment/success/{membership}', [\App\Http\Controllers\EnrollmentController::class, 'paymentSuccess'])->name('enrollment.payment.success');
Route::get('/enrollment/payment/cancel/{membership}', [\App\Http\Controllers\EnrollmentController::class, 'paymentCancel'])->name('enrollment.payment.cancel');

// Payment Gateway Routes
Route::get('/payment-gateway', [PaymentGatewayController::class, 'show'])->name('payment.gateway');
Route::post('/payment-gateway/process', [PaymentGatewayController::class, 'process'])->name('payment.process');
Route::post('/payment-webhook', [PaymentGatewayController::class, 'webhook'])->name('payment.webhook');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Members Management
    Route::resource('members', MemberController::class);

    // Additional member routes
    Route::patch('members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggle-status');
    Route::post('members/bulk-action', [MemberController::class, 'bulkAction'])->name('members.bulk-action');
    Route::get('members/export', [MemberController::class, 'export'])->name('members.export');

    // Trainers Management
    Route::resource('trainers', AdminTrainerController::class);

    // Memberships Management
    Route::resource('memberships', MembershipController::class);

    // Workouts Management
    Route::resource('workouts', WorkoutController::class);

    // Exercises Management
    Route::resource('exercises', ExerciseController::class);
});

// Trainer Routes
Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', [TrainerController::class, 'dashboard'])->name('dashboard');
    Route::get('/clients', [TrainerController::class, 'clients'])->name('clients');
    Route::get('/workouts', [TrainerController::class, 'workouts'])->name('workouts');
    Route::get('/workouts/create', [TrainerController::class, 'createWorkout'])->name('workouts.create');
    Route::post('/workouts', [TrainerController::class, 'storeWorkout'])->name('workouts.store');
    Route::get('/schedule', [TrainerController::class, 'schedule'])->name('schedule');
    Route::get('/client-progress/{client?}', [TrainerController::class, 'clientProgress'])->name('client-progress');
});

// Member Routes
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\MemberController::class, 'dashboard'])->name('dashboard');

    // Workout management
    Route::get('/workouts', [\App\Http\Controllers\MemberController::class, 'workouts'])->name('workouts');
    Route::get('/workouts/book', [\App\Http\Controllers\MemberController::class, 'bookWorkout'])->name('workouts.book');
    Route::post('/workouts', [\App\Http\Controllers\MemberController::class, 'storeWorkout'])->name('workouts.store');
    Route::get('/workouts/{workout}', [\App\Http\Controllers\MemberController::class, 'showWorkout'])->name('workouts.show');
    Route::patch('/workouts/{workout}/cancel', [\App\Http\Controllers\MemberController::class, 'cancelWorkout'])->name('workouts.cancel');

    // Exercise browsing
    Route::get('/exercises', [\App\Http\Controllers\MemberController::class, 'browseExercises'])->name('exercises');
    Route::get('/exercises/{exercise}', [\App\Http\Controllers\MemberController::class, 'showExercise'])->name('exercises.show');

    // Progress tracking
    Route::get('/progress', [\App\Http\Controllers\MemberController::class, 'progress'])->name('progress');

    // Profile management
    Route::patch('/profile', [\App\Http\Controllers\MemberController::class, 'updateProfile'])->name('profile.update');
});

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
