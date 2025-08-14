<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer\PaymentRecord;

class PaymentPolicy
{
    public function view(User $user, PaymentRecord $payment)
    {
        return $user->isAdmin() || $payment->member_id === $user->id;
    }

    public function create(User $user)
    {
        return $user->isMember();
    }

    public function update(User $user, PaymentRecord $payment)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PaymentRecord $payment)
    {
        return $user->isAdmin();
    }
}