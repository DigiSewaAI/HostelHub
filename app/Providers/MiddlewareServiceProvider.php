<?php

namespace App\Providers;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\RoleMiddleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Manually register role middleware
        $this->app->singleton('role', function ($app) {
            return new RoleMiddleware();
        });
    }
}
