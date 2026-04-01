<?php

namespace App\Http\Middleware;

use App\Exceptions\RestaurantAccessException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->hasRole('super_admin')) {
            return $next($request);
        }

        // Abonelik artık kullanıcı üzerinde tutuluyor
        if (! $user->hasActiveSubscription()) {
            throw RestaurantAccessException::subscriptionRequired();
        }

        return $next($request);
    }
}
