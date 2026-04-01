<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function before(User $user): ?bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->accessibleRestaurants()->exists();
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasRestaurantAccess($branch->restaurant_id);
    }

    public function create(User $user): bool
    {
        return $user->accessibleRestaurants()->exists();
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->hasRestaurantAccess($branch->restaurant_id);
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasRestaurantAccess($branch->restaurant_id)
            && ! $branch->is_main;  // Ana şube silinemez
    }
}
