<?php

use App\Http\Controllers\Trainer\DashboardController;
use App\Http\Controllers\Trainer\WorkoutController;
use App\Http\Controllers\Trainer\BookingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:trainer'])->prefix('trainer')->name('trainer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Workout Management (CRUD)
    Route::prefix('workouts')->name('workouts.')->group(function () {
        Route::get('/', [WorkoutController::class, 'index'])->name('index');
        Route::get('/create', [WorkoutController::class, 'create'])->name('create');
        Route::post('/', [WorkoutController::class, 'store'])->name('store');
        Route::get('/{workout}', [WorkoutController::class, 'show'])->name('show');
        Route::get('/{workout}/edit', [WorkoutController::class, 'edit'])->name('edit');
        Route::put('/{workout}', [WorkoutController::class, 'update'])->name('update');
        Route::delete('/{workout}', [WorkoutController::class, 'destroy'])->name('destroy');
        Route::patch('/{workout}/complete', [WorkoutController::class, 'markCompleted'])->name('complete');
        Route::post('/{workout}/duplicate', [WorkoutController::class, 'duplicate'])->name('duplicate');
    });
    
    // Booking Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::patch('/{booking}/status', [BookingController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{booking}/notes', [BookingController::class, 'addNotes'])->name('add-notes');
        
        // Calendar Views
        Route::get('/calendar/week', [BookingController::class, 'calendar'])->name('calendar');
        Route::get('/calendar/day', [BookingController::class, 'dayView'])->name('day-view');
        
        // Notifications
        Route::get('/notifications', [BookingController::class, 'notifications'])->name('notifications');
    });
    
});