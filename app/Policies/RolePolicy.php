<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function admin(User $user)
    {
        return $user->role === 'admin';
    }
}
