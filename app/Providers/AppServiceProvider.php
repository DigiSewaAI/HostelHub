<?php

namespace App\Providers;

use App\Services\PlanLimitService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\AdminNavLink;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register PlanLimitService as a singleton
        $this->app->singleton(PlanLimitService::class, function ($app) {
            return new PlanLimitService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Blade component
        Blade::component('admin-nav-link', AdminNavLink::class);

        // Additional bootstrapping code can go here
    }
}
