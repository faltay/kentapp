<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// ── Guest routes ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    // Brute-force koruması: IP başına 1 dakikada 10 deneme
    Route::post('login', [LoginController::class, 'store'])->middleware('throttle:10,1');

    Route::get('register', [RegisterController::class, 'create'])->name('register');
    // Spam koruması: IP başına 1 dakikada 5 kayıt
    Route::post('register', [RegisterController::class, 'store'])->middleware('throttle:5,1');

    Route::get('forgot-password', [PasswordResetController::class, 'requestForm'])->name('password.request');
    // Şifre sıfırlama kötüye kullanım koruması: 1 dakikada 5 istek
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');

    Route::get('reset-password/{token}', [PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ── Auth routes ───────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});
