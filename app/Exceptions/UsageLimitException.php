<?php

namespace App\Exceptions;

use Exception;

class UsageLimitException extends Exception
{
    public static function exceeded(string $feature, int $limit): static
    {
        return new static(
            __('common.errors.usage_limit_exceeded', ['feature' => $feature, 'limit' => $limit]),
            403
        );
    }
}
