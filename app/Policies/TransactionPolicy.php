<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin() || $transaction->member_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isMember();
    }

    public function complete(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin() || $user->isCashier();
    }

    public function cancel(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin() || ($user->isMember() && $transaction->member_id === $user->id);
    }

}
