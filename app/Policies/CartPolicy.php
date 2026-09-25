<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isMember();
    }

    public function view(User $user, Cart $cart): bool
    {
        return $user->isMember() && $cart->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isMember();
    }
}
