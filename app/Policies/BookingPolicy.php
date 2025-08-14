<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Trainer\TrainerBooking;

class BookingPolicy
{
    public function view(User $user, TrainerBooking $booking)
    {
        return $booking->trainer_id === $user->id || $booking->member_id === $user->id;
    }

    public function update(User $user, TrainerBooking $booking)
    {
        if ($user->isTrainer()) {
            return $booking->trainer_id === $user->id;
        }
        
        if ($user->isMember()) {
            return $booking->member_id === $user->id;
        }
        
        return false;
    }

    public function delete(User $user, TrainerBooking $booking)
    {
        return $user->isAdmin() || 
               ($user->isMember() && $booking->member_id === $user->id);
    }
}