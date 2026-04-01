<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function (): void {

    // Dil değiştirme
    Route::get('lang/{locale}', function (string $locale, \Illuminate\Http\Request $request) {
        if (! array_key_exists($locale, LaravelLocalization::getSupportedLocales())) {
            return redirect()->route('home');
        }

        session(['locale' => $locale]);

        $from = $request->query('from', route('home'));

        $redirectUrl = LaravelLocalization::getLocalizedURL($locale, $from);

        return redirect($redirectUrl ?: route('home'));
    })->name('lang.switch');

    // Ana sayfa — Pages modülünden dinamik
    Route::get('/', [\App\Http\Controllers\Public\StaticPageController::class, 'home'])->name('home');

    // Auth rotaları
    require __DIR__ . '/auth.php';

    // ── Blog ─────────────────────────────────────────────────────────────
    Route::prefix('blog')->name('blog.')->group(function (): void {
        Route::get('/', [\App\Http\Controllers\Public\BlogController::class, 'index'])->name('index');
        Route::get('{slug}', [\App\Http\Controllers\Public\BlogController::class, 'show'])->name('show');
    });

    // ── Statik Sayfalar ──────────────────────────────────────────────────
    Route::prefix('pages')->name('pages.')->group(function (): void {
        Route::get('{slug}', [\App\Http\Controllers\Public\StaticPageController::class, 'show'])->name('show');
    });

    // ── Auth gerektiren alanlar ──────────────────────────────────────────
    Route::middleware(['auth', 'verified'])->group(function (): void {

        // ── Impersonate — Çıkış (herhangi bir panelden erişilebilir) ────
        Route::get('impersonate/stop', [\App\Http\Controllers\Admin\ImpersonateController::class, 'stop'])
            ->name('impersonate.stop');

        // ── Super Admin Paneli ───────────────────────────────────────────
        Route::prefix('admin')
            ->name('admin.')
            ->middleware('role:super_admin')
            ->group(base_path('routes/admin.php'));
    });
});
