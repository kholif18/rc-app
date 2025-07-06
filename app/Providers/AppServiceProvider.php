<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\AppSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('cache')) {
            $settings = Cache::remember('app_settings', now()->addDay(), function () {
                return \App\Models\Setting::first();
            });

            view()->share('setting', $settings);
        }

        view()->share('app_version', AppSetting::get('app_version', config('app.version')));
    }
}
