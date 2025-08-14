<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Account Management
    Route::prefix('accounts')->name('accounts.')->group(function () {
        // Members
        Route::prefix('members')->name('members.')->group(function () {
            Route::get('/', [AccountController::class, 'members'])->name('index');
            Route::get('/create', [AccountController::class, 'createMember'])->name('create');
            Route::post('/', [AccountController::class, 'storeMember'])->name('store');
            Route::get('/{member}', [AccountController::class, 'showMember'])->name('show');
            Route::get('/{member}/edit', [AccountController::class, 'editMember'])->name('edit');
            Route::put('/{member}', [AccountController::class, 'updateMember'])->name('update');
            Route::delete('/{member}', [AccountController::class, 'destroyMember'])->name('destroy');
        });
        
        // Trainers
        Route::prefix('trainers')->name('trainers.')->group(function () {
            Route::get('/', [AccountController::class, 'trainers'])->name('index');
            Route::get('/create', [AccountController::class, 'createTrainer'])->name('create');
            Route::post('/', [AccountController::class, 'storeTrainer'])->name('store');
            Route::get('/{trainer}', [AccountController::class, 'showTrainer'])->name('show');
            Route::get('/{trainer}/edit', [AccountController::class, 'editTrainer'])->name('edit');
            Route::put('/{trainer}', [AccountController::class, 'updateTrainer'])->name('update');
            Route::delete('/{trainer}', [AccountController::class, 'destroyTrainer'])->name('destroy');
        });
        
        // Toggle user status
        Route::patch('/users/{user}/toggle-status', [AccountController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    // Payment Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [PaymentController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [PaymentController::class, 'exportCsv'])->name('export.csv');
        Route::get('/reports/generate', [PaymentController::class, 'generateReport'])->name('reports.generate');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/members', [ReportController::class, 'memberReport'])->name('members');
        Route::get('/trainers', [ReportController::class, 'trainerReport'])->name('trainers');
        Route::get('/bookings', [ReportController::class, 'bookingReport'])->name('bookings');
        Route::get('/revenue', [ReportController::class, 'revenueReport'])->name('revenue');
        Route::get('/export/csv', [ReportController::class, 'exportCsv'])->name('export.csv');
    });
    
});