<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\ValidatePathEncoding::class,
    ];

    /**
     * Middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [ 
        ],
    ];

    /**
     * Route middleware aliases.
     */
    protected $middlewareAliases = [
        // Authentication
        'auth'              => \App\Http\Middleware\Authenticate::class,
        'auth.basic'        => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session'      => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'guest'             => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'verified'          => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'password.confirm'  => \Illuminate\Auth\Middleware\RequirePassword::class,

        // Authorization & Roles
        'can'               => \Illuminate\Auth\Middleware\Authorize::class,
        'role'              => \App\Http\Middleware\RoleMiddleware::class,
        'admin'             => \App\Http\Middleware\AdminMiddleware::class,

        // API & Performance
        'throttle'          => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'cache.headers'     => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'signed'            => \App\Http\Middleware\ValidateSignature::class,

        // Custom Middleware for HostelHub
        'localize'          => \App\Http\Middleware\Localize::class,
        'hostel.owner'      => \App\Http\Middleware\HostelOwner::class,
        'payment.verified'  => \App\Http\Middleware\PaymentVerified::class,
    ];
}
