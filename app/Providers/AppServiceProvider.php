<?php

namespace App\Providers;

use App\Listeners\SendWelcomeEmail;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(Registered::class, SendWelcomeEmail::class);

        View::composer('*', function ($view) {
            if (! $view->offsetExists('activeLanguages')) {
                try {
                    $view->with('activeLanguages', Language::getActiveLanguages());
                    $view->with('defaultLanguageCode', Language::getDefaultCode());
                } catch (\Exception) {
                    $view->with('activeLanguages', collect());
                    $view->with('defaultLanguageCode', 'en');
                }
            }

            if (! $view->offsetExists('appSettings')) {
                try {
                    $view->with('appSettings', Setting::getAll());
                } catch (\Exception) {
                    $view->with('appSettings', []);
                }
            }
        });
    }
}
