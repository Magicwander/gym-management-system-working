<?php

use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:member'])->prefix('customer')->name('customer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Booking Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::get('/{booking}', [BookingController::class, 'show'])->name('show');
        Route::get('/{booking}/payment', [BookingController::class, 'payment'])->name('payment');
        Route::patch('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::patch('/{booking}/reschedule', [BookingController::class, 'reschedule'])->name('reschedule');
        
        // Booking Views
        Route::get('/history', [BookingController::class, 'history'])->name('history');
        Route::get('/upcoming', [BookingController::class, 'upcoming'])->name('upcoming');
    });
    
    // Payment Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/{booking}', [PaymentController::class, 'show'])->name('show');
        Route::post('/{booking}/process', [PaymentController::class, 'process'])->name('process');
        Route::get('/{payment}/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/history', [PaymentController::class, 'history'])->name('history');
        Route::get('/{payment}/receipt', [PaymentController::class, 'receipt'])->name('receipt');
    });
    
});

// Public customer registration
Route::get('/register/customer', function () {
    return view('auth.register-customer');
})->name('register.customer');

Route::post('/register/customer', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|in:male,female,other',
        'address' => 'nullable|string',
        'emergency_contact' => 'nullable|string|max:255',
        'emergency_phone' => 'nullable|string|max:20',
        'medical_conditions' => 'nullable|string',
        'fitness_goals' => 'nullable|string',
    ]);
    
    $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
    $validated['role'] = 'member';
    $validated['is_active'] = true;
    
    $user = \App\Models\User::create($validated);
    
    \Illuminate\Support\Facades\Auth::login($user);
    
    return redirect()->route('customer.dashboard')
        ->with('success', 'Welcome to Hermes Fitness! Your account has been created successfully.');
})->name('register.customer.store');