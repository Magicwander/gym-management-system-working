<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Trainer\Workout;

class WorkoutPolicy
{
    public function view(User $user, Workout $workout)
    {
        return $user->isTrainer() && $workout->trainer_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->isTrainer();
    }

    public function update(User $user, Workout $workout)
    {
        return $user->isTrainer() && $workout->trainer_id === $user->id;
    }

    public function delete(User $user, Workout $workout)
    {
        return $user->isTrainer() && $workout->trainer_id === $user->id;
    }
}