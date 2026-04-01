<?php

use App\Http\Controllers\Api\V1;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes  (prefix: /api/v1, name: api.v1.)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('v1.')->group(function (): void {

    // ── Public — throttle: 60 req/dk ────────────────────────────────────────
    Route::middleware('throttle:60,1')->group(function (): void {

        // Auth
        Route::post('auth/register', [V1\AuthController::class, 'register'])->name('auth.register');
        Route::post('auth/login',    [V1\AuthController::class, 'login'])->name('auth.login');

        // Kontör paketleri
        Route::get('credit-packages', [V1\CreditPackageController::class, 'index'])->name('credit-packages.index');

        // Konum hiyerarşisi
        Route::get('locations/provinces',     [V1\LocationController::class, 'provinces'])->name('locations.provinces');
        Route::get('locations/districts',     [V1\LocationController::class, 'districts'])->name('locations.districts');
        Route::get('locations/neighborhoods', [V1\LocationController::class, 'neighborhoods'])->name('locations.neighborhoods');
    });

    // ── Authenticated — throttle: 1000 req/dk ───────────────────────────────
    Route::middleware(['auth:sanctum', 'throttle:1000,1'])->group(function (): void {

        // Auth
        Route::post('auth/logout',     [V1\AuthController::class, 'logout'])->name('auth.logout');
        Route::get('auth/me',          [V1\AuthController::class, 'me'])->name('auth.me');
        Route::put('auth/me',          [V1\AuthController::class, 'updateMe'])->name('auth.me.update');
        Route::put('auth/me/password', [V1\AuthController::class, 'updatePassword'])->name('auth.me.password');
        Route::post('auth/me/avatar',  [V1\AuthController::class, 'updateAvatar'])->name('auth.me.avatar');

        // İlanlar (browsing — contractor / agent)
        Route::get('listings',                   [V1\ListingController::class, 'index'])->name('listings.index');
        Route::get('listings/featured',          [V1\ListingController::class, 'featured'])->name('listings.featured');
        Route::get('listings/{listing}',         [V1\ListingController::class, 'show'])->name('listings.show');
        Route::post('listings/{listing}/unlock', [V1\ListingController::class, 'unlock'])->name('listings.unlock');

        // Benim ilanlarım (land_owner / agent)
        Route::get('my/listings',                 [V1\MyListingController::class, 'index'])->name('my.listings.index');
        Route::post('my/listings',                [V1\MyListingController::class, 'store'])->name('my.listings.store');
        Route::get('my/listings/{listing}',       [V1\MyListingController::class, 'show'])->name('my.listings.show');
        Route::post('my/listings/{listing}',      [V1\MyListingController::class, 'update'])->name('my.listings.update');
        Route::delete('my/listings/{listing}',    [V1\MyListingController::class, 'destroy'])->name('my.listings.destroy');
        Route::get('my/listings/{listing}/views', [V1\MyListingController::class, 'views'])->name('my.listings.views');

        // Dashboard istatistikleri
        Route::get('my/stats', [V1\StatsController::class, 'index'])->name('my.stats');

        // Profil (contractor / agent)
        Route::get('profile',              [V1\ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile',              [V1\ProfileController::class, 'update'])->name('profile.update');
        Route::post('profile/certificate', [V1\ProfileController::class, 'uploadCertificate'])->name('profile.certificate');

        // Çalışma bölgeleri (contractor / agent)
        Route::get('profile/neighborhoods',            [V1\NeighborhoodController::class, 'index'])->name('profile.neighborhoods.index');
        Route::post('profile/neighborhoods',           [V1\NeighborhoodController::class, 'store'])->name('profile.neighborhoods.store');
        Route::delete('profile/neighborhoods/{index}', [V1\NeighborhoodController::class, 'destroy'])->name('profile.neighborhoods.destroy');

        // Kontör
        Route::get('credits/balance',      [V1\CreditController::class, 'balance'])->name('credits.balance');
        Route::get('credits/transactions', [V1\CreditController::class, 'transactions'])->name('credits.transactions');
        Route::post('credits/purchase',     [V1\CreditController::class, 'purchase'])->name('credits.purchase');
        Route::post('credits/test-purchase',[V1\CreditController::class, 'testPurchase'])->name('credits.test-purchase');

        // Müteahhit / Danışman public profil & yorumlar
        Route::get('contractors',                [V1\ContractorController::class, 'index'])->name('contractors.index');
        Route::get('contractors/{user}',         [V1\ContractorController::class, 'show'])->name('contractors.show');
        Route::get('contractors/{user}/reviews', [V1\ReviewController::class, 'index'])->name('contractors.reviews');

        // Değerlendirme
        Route::post('reviews', [V1\ReviewController::class, 'store'])->name('reviews.store');

        // AI Chat
        Route::get('chat',          [V1\ChatController::class, 'conversation'])->name('chat.conversation');
        Route::post('chat/message', [V1\ChatController::class, 'message'])->name('chat.message');

        // Bildirimler
        Route::get('notifications',                         [V1\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/send-listing',           [V1\NotificationController::class, 'sendListing'])->name('notifications.send-listing');
        Route::post('notifications/read-all',               [V1\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::post('notifications/{id}/read',              [V1\NotificationController::class, 'markRead'])->name('notifications.read');
    });

    // ── Ödeme callback — CSRF muaf ───────────────────────────────────────────
    Route::post('payments/iyzico/callback', [V1\PaymentCallbackController::class, 'iyzico'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('payments.iyzico.callback');
});
