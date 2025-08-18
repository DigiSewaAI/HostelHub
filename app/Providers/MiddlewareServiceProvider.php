<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureOrgContext;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register()
    {
        // Bind EnsureOrgContext middleware
        $this->app->bind(EnsureOrgContext::class, function ($app) {
            return new EnsureOrgContext();
        });

        // Bind RoleMiddleware
        $this->app->bind(RoleMiddleware::class, function ($app) {
            return new RoleMiddleware();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Optionally you can alias them if you want to use short keys
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);
        $this->app['router']->aliasMiddleware('org.context', EnsureOrgContext::class);
    }
}
