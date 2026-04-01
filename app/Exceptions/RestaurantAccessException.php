<?php

namespace App\Exceptions;

use Exception;

class RestaurantAccessException extends Exception
{
    public const UNAUTHORIZED = 'unauthorized';
    public const SUSPENDED = 'suspended';
    public const SUBSCRIPTION_REQUIRED = 'subscriptionRequired';

    public static function unauthorized(): static
    {
        return new static(__('common.errors.restaurant_access_denied'), 403);
    }

    public static function suspended(): static
    {
        return new static(__('common.errors.restaurant_suspended'), 403);
    }

    public static function subscriptionRequired(): static
    {
        return new static(__('common.errors.subscription_required'), 402);
    }
}
