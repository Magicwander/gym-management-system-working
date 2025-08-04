<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'is_active',
        'emergency_contact',
        'emergency_phone',
        'medical_conditions',
        'fitness_goals',
        'trainer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is trainer
     */
    public function isTrainer(): bool
    {
        return $this->role === 'trainer';
    }

    /**
     * Check if user is member
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * User's memberships
     */
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * User's workouts (as member)
     */
    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    /**
     * Workouts assigned by this trainer
     */
    public function assignedWorkouts()
    {
        return $this->hasMany(Workout::class, 'trainer_id');
    }

    /**
     * Get active membership
     */
    public function activeMembership()
    {
        return $this->memberships()->where('status', 'active')
                    ->where('end_date', '>=', now()->toDateString())
                    ->first();
    }
}
