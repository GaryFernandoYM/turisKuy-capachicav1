<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}
