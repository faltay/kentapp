<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * super_admin tüm policy'leri bypass eder.
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->hasRole('super_admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, User $model): bool
    {
        // Kullanıcı kendi profilini güncelleyebilir
        return $user->id === $model->id || $user->hasRole('super_admin');
    }

    public function delete(User $user, User $model): bool
    {
        // Kendi hesabını kimse silemez
        if ($user->id === $model->id) {
            return false;
        }

        return false;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('super_admin');
    }
}
