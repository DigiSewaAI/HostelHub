<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CentralPasswordBroker;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class CentralPasswordResetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new CentralPasswordBrokerManager($app);
        });

        $this->app->bind('auth.password.broker', function ($app) {
            return $app->make('auth.password')->broker();
        });
    }

    public function boot()
    {
        //
    }
}
