<?php

namespace App\Providers;

use App\Models\Trainer\Workout;
use App\Models\Trainer\TrainerBooking;
use App\Models\Customer\PaymentRecord;
use App\Policies\WorkoutPolicy;
use App\Policies\BookingPolicy;
use App\Policies\PaymentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Workout::class => WorkoutPolicy::class,
        TrainerBooking::class => BookingPolicy::class,
        PaymentRecord::class => PaymentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}