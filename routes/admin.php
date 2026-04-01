<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Panel Routes  (prefix: /admin, name: admin.)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

// Modül 1: Kullanıcı Yönetimi
Route::resource('users', Admin\UserController::class);
Route::post('users/{user}/impersonate', [Admin\ImpersonateController::class, 'start'])->name('users.impersonate');

// Modül 1c: Arsa Sahibi Yönetimi
Route::get('land-owners', [Admin\LandOwnerController::class, 'index'])->name('land-owners.index');
Route::get('land-owners/create', [Admin\LandOwnerController::class, 'create'])->name('land-owners.create');
Route::post('land-owners', [Admin\LandOwnerController::class, 'store'])->name('land-owners.store');
Route::get('land-owners/{land_owner}', [Admin\LandOwnerController::class, 'show'])->name('land-owners.show');
Route::get('land-owners/{land_owner}/edit', [Admin\LandOwnerController::class, 'edit'])->name('land-owners.edit');
Route::put('land-owners/{land_owner}', [Admin\LandOwnerController::class, 'update'])->name('land-owners.update');
Route::delete('land-owners/{land_owner}', [Admin\LandOwnerController::class, 'destroy'])->name('land-owners.destroy');

// Modül 1d: Emlak Danışmanı Yönetimi
Route::get('agents', [Admin\AgentController::class, 'index'])->name('agents.index');
Route::get('agents/create', [Admin\AgentController::class, 'create'])->name('agents.create');
Route::post('agents', [Admin\AgentController::class, 'store'])->name('agents.store');
Route::get('agents/{agent}', [Admin\AgentController::class, 'show'])->name('agents.show');
Route::get('agents/{agent}/edit', [Admin\AgentController::class, 'edit'])->name('agents.edit');
Route::put('agents/{agent}', [Admin\AgentController::class, 'update'])->name('agents.update');
Route::delete('agents/{agent}', [Admin\AgentController::class, 'destroy'])->name('agents.destroy');

// Modül 1b: Müteahhit Yönetimi
Route::get('contractors', [Admin\ContractorController::class, 'index'])->name('contractors.index');
Route::get('contractors/create', [Admin\ContractorController::class, 'create'])->name('contractors.create');
Route::post('contractors', [Admin\ContractorController::class, 'store'])->name('contractors.store');
Route::get('contractors/{contractor}', [Admin\ContractorController::class, 'show'])->name('contractors.show');
Route::get('contractors/{contractor}/edit', [Admin\ContractorController::class, 'edit'])->name('contractors.edit');
Route::put('contractors/{contractor}', [Admin\ContractorController::class, 'update'])->name('contractors.update');
Route::post('contractors/{contractor}/approve-certificate', [Admin\ContractorController::class, 'approveCertificate'])->name('contractors.approve-certificate');
Route::post('contractors/{contractor}/reject-certificate',  [Admin\ContractorController::class, 'rejectCertificate'])->name('contractors.reject-certificate');
Route::delete('contractors/{contractor}', [Admin\ContractorController::class, 'destroy'])->name('contractors.destroy');

// Modül 2: İlan Yönetimi
Route::get('listings', [Admin\ListingController::class, 'index'])->name('listings.index');
Route::get('listings/create', [Admin\ListingController::class, 'create'])->name('listings.create');
Route::post('listings', [Admin\ListingController::class, 'store'])->name('listings.store');
Route::get('listings/{listing}/edit', [Admin\ListingController::class, 'edit'])->name('listings.edit');
Route::put('listings/{listing}', [Admin\ListingController::class, 'update'])->name('listings.update');
Route::get('listings/{listing}', [Admin\ListingController::class, 'show'])->name('listings.show');
Route::post('listings/{listing}/approve', [Admin\ListingController::class, 'approve'])->name('listings.approve');
Route::post('listings/{listing}/reject', [Admin\ListingController::class, 'reject'])->name('listings.reject');
Route::post('listings/{listing}/passive', [Admin\ListingController::class, 'passive'])->name('listings.passive');
Route::post('listings/{listing}/toggle-featured', [Admin\ListingController::class, 'toggleFeatured'])->name('listings.toggle-featured');
Route::delete('listings/{listing}', [Admin\ListingController::class, 'destroy'])->name('listings.destroy');


// Modül 4: Kontör Paketleri
Route::resource('credit-packages', Admin\CreditPackageController::class)->except(['show']);

// Modül 5: Kontör İşlemleri
Route::get('credit-transactions', [Admin\CreditTransactionController::class, 'index'])->name('credit-transactions.index');
Route::get('credit-transactions/create', [Admin\CreditTransactionController::class, 'create'])->name('credit-transactions.create');
Route::post('credit-transactions', [Admin\CreditTransactionController::class, 'store'])->name('credit-transactions.store');
Route::get('credit-transactions/users-search', [Admin\CreditTransactionController::class, 'searchUsers'])->name('credit-transactions.users-search');

// Modül 6: Ödemeler
Route::get('payments', [Admin\PaymentController::class, 'index'])->name('payments.index');

// Modül 7: Değerlendirme Moderasyonu
Route::get('reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
Route::post('reviews/{review}/approve', [Admin\ReviewController::class, 'approve'])->name('reviews.approve');
Route::post('reviews/{review}/reject', [Admin\ReviewController::class, 'reject'])->name('reviews.reject');
Route::delete('reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

// Blog & Sayfalar
Route::post('blog-categories/reorder', [Admin\BlogCategoryController::class, 'reorder'])->name('blog-categories.reorder');
Route::post('blog-categories/{blog_category}/toggle-active', [Admin\BlogCategoryController::class, 'toggleActive'])->name('blog-categories.toggle-active');
Route::resource('blog-categories', Admin\BlogCategoryController::class)->except(['show']);

Route::post('posts/{post}/toggle-publish', [Admin\PostController::class, 'togglePublish'])->name('posts.toggle-publish');
Route::resource('posts', Admin\PostController::class)->except(['show']);
Route::resource('pages', Admin\PageController::class)->except(['show']);

// Dil Yönetimi
Route::post('languages/{language}/toggle-active', [Admin\LanguageController::class, 'toggleActive'])->name('languages.toggle-active');
Route::resource('languages', Admin\LanguageController::class)->except(['show']);

// Yardımcı: Konum Arama
Route::get('locations/search', [Admin\LocationController::class, 'search'])->name('locations.search');
Route::get('locations/districts', [Admin\LocationController::class, 'districts'])->name('locations.districts');
Route::get('locations/neighborhoods', [Admin\LocationController::class, 'neighborhoods'])->name('locations.neighborhoods');

// AI Asistan
Route::get('ai/settings',              [Admin\AiController::class, 'settings'])->name('ai.settings');
Route::post('ai/settings',             [Admin\AiController::class, 'updateSettings'])->name('ai.settings.update');
Route::get('ai/prompt',                [Admin\AiController::class, 'prompt'])->name('ai.prompt');
Route::post('ai/prompt',               [Admin\AiController::class, 'updatePrompt'])->name('ai.prompt.update');

Route::get('ai/conversations',                          [Admin\ChatConversationController::class, 'index'])->name('ai.conversations.index');
Route::get('ai/conversations/list',                     [Admin\ChatConversationController::class, 'list'])->name('ai.conversations.list');
Route::get('ai/conversations/{conversation}',           [Admin\ChatConversationController::class, 'show'])->name('ai.conversations.show');
Route::get('ai/conversations/{conversation}/detail',    [Admin\ChatConversationController::class, 'detail'])->name('ai.conversations.detail');
Route::post('ai/conversations/{conversation}/reply',    [Admin\ChatConversationController::class, 'reply'])->name('ai.conversations.reply');
Route::post('ai/conversations/{conversation}/toggle-ai',[Admin\ChatConversationController::class, 'toggleAi'])->name('ai.conversations.toggle-ai');
Route::post('ai/conversations/{conversation}/close',    [Admin\ChatConversationController::class, 'close'])->name('ai.conversations.close');
Route::post('ai/conversations/{conversation}/reopen',   [Admin\ChatConversationController::class, 'reopen'])->name('ai.conversations.reopen');

// Sistem Ayarları
Route::get('settings', [Admin\SettingsController::class, 'edit'])->name('settings.edit');
Route::put('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
