<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;

class RestaurantPolicy
{
    /**
     * super_admin tüm kontrollerden muaf.
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
        return $user->hasRole('restaurant_owner');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('restaurant_owner');
    }

    public function view(User $user, Restaurant $restaurant): bool
    {
        return $user->hasRestaurantAccess($restaurant->id);
    }

    public function update(User $user, Restaurant $restaurant): bool
    {
        return $user->hasRestaurantAccess($restaurant->id);
    }

    public function delete(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }
}
