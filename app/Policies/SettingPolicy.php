<?php

namespace App\Policies;

use App\Models\User;

class SettingPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    public function update(User $user): bool
    {
        return false;
    }
}
