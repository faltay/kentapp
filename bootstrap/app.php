<?php

use App\Exceptions\RestaurantAccessException;
use App\Exceptions\UsageLimitException;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\CheckUsageLimits;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check_subscription'      => CheckSubscription::class,
            'check_usage_limits'      => CheckUsageLimits::class,
            // Spatie Permission
            'role'                    => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'              => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission'      => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            // LaravelLocalization
            'localeSessionRedirect'   => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localizationRedirect'    => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeViewPath'          => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // RestaurantAccessException
        $exceptions->render(function (RestaurantAccessException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 403);
            }
            return redirect()->route('home')->with('error', $e->getMessage());
        });

        // UsageLimitException
        $exceptions->render(function (UsageLimitException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 403);
            }
            return back()->with('error', $e->getMessage());
        });

        // ValidationException — API isteklerinde düzgün formatlı JSON döndür
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors'  => $e->errors(),
                ], 422);
            }
        });
    })->create();

$app->usePublicPath($app->basePath('public_html'));

return $app;
