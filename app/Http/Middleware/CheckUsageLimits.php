<?php

namespace App\Http\Middleware;

use App\Exceptions\UsageLimitException;
use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUsageLimits
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (! $user || $user->hasRole('super_admin')) {
            return $next($request);
        }

        // Restoranı belirle: request'teki current_restaurant veya form field'daki restaurant_id
        $restaurant = $request->current_restaurant;

        if (! $restaurant) {
            $rid = $request->input('restaurant_id');
            $restaurant = $rid ? Restaurant::find($rid) : null;
        }

        if (! $restaurant) {
            return $next($request);
        }

        // Plan limiti artık kullanıcının aktif planından geliyor
        $plan  = $user->activePlan();
        $limit = $plan ? $plan->getLimit($feature) : $this->defaultLimit($feature);

        // -1 = sınırsız
        if ($limit === -1) {
            return $next($request);
        }

        $current = match ($feature) {
            'branch'     => $restaurant->branches()->count(),
            'restaurant' => $user->ownedRestaurants()->count(),
            default      => 0,
        };

        if ($current >= $limit) {
            throw UsageLimitException::exceeded($feature, $limit);
        }

        return $next($request);
    }

    private function defaultLimit(string $feature): int
    {
        return match ($feature) {
            'branch'     => 1,
            'restaurant' => 1,
            default      => 0,
        };
    }
}
