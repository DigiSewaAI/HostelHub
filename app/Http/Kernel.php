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
        \App\Http\Middleware\Localize::class,
        \App\Http\Middleware\SecurityHeaders::class, // 🆕 नयाँ security headers middleware
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\EnsureOrgContext::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        // 🆕 नयाँ middleware group for owner routes
        'owner' => [
            'auth',
            'role:owner',
            'subscription.active',
        ],

        // 🆕 नयाँ middleware group for admin routes  
        'admin' => [
            'auth',
            'role:admin',
        ],

        // 🆕 नयाँ middleware group for student routes
        'student' => [
            'auth',
            'role:student',
        ],
    ];

    /**
     * The application's route middleware aliases.
     */
    protected $middlewareAliases = [
        // Laravel Default Middlewares
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Spatie Permission Middlewares
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,

        // Custom Application Middlewares
        'checkrole' => \App\Http\Middleware\CheckRole::class,
        'role.multiple' => \App\Http\Middleware\RoleMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,

        // 🆕 नयाँ Custom Middlewares
        'check.permission' => \App\Http\Middleware\CheckPermission::class,
        'check.role.or.permission' => \App\Http\Middleware\CheckRoleOrPermission::class,

        // Subscription & Plan Middlewares
        'subscription.active' => \App\Http\Middleware\EnsureSubscriptionActive::class,
        'subscription.limit' => \App\Http\Middleware\EnforcePlanLimits::class,
        'plan.limits' => \App\Http\Middleware\EnforcePlanLimits::class,
        'enforce.plan.limits' => \App\Http\Middleware\EnforcePlanLimits::class,

        // Payment Middlewares
        'payment.verified' => \App\Http\Middleware\PaymentVerified::class,

        // Hostel & Organization Middlewares
        'check.hostel.limit' => \App\Http\Middleware\CheckHostelLimit::class,
        'org.context' => \App\Http\Middleware\EnsureOrgContext::class,
        'hasOrganization' => \App\Http\Middleware\CheckTenantAccess::class,

        // Localization & Security
        'localize' => \App\Http\Middleware\Localize::class,
        'security.headers' => \App\Http\Middleware\SecurityHeaders::class, // 🆕 नयाँ
    ];

    /**
     * The application's route middleware.
     * This is for backward compatibility with some Laravel versions.
     */
    protected $routeMiddleware = [
        // Laravel Default Middlewares
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Spatie Permission Middlewares
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,

        // Custom Application Middlewares
        'checkrole' => \App\Http\Middleware\CheckRole::class,
        'role.multiple' => \App\Http\Middleware\RoleMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,

        // 🆕 नयाँ Custom Middlewares
        'check.permission' => \App\Http\Middleware\CheckPermission::class,
        'check.role.or.permission' => \App\Http\Middleware\CheckRoleOrPermission::class,

        // Subscription & Plan Middlewares
        'subscription.active' => \App\Http\Middleware\EnsureSubscriptionActive::class,
        'subscription.limit' => \App\Http\Middleware\EnforcePlanLimits::class,
        'plan.limits' => \App\Http\Middleware\EnforcePlanLimits::class,
        'enforce.plan.limits' => \App\Http\Middleware\EnforcePlanLimits::class,

        // Payment Middlewares
        'payment.verified' => \App\Http\Middleware\PaymentVerified::class,

        // Hostel & Organization Middlewares
        'check.hostel.limit' => \App\Http\Middleware\CheckHostelLimit::class,
        'org.context' => \App\Http\Middleware\EnsureOrgContext::class,
        'hasOrganization' => \App\Http\Middleware\CheckTenantAccess::class,

        // Localization & Security
        'localize' => \App\Http\Middleware\Localize::class,
        'security.headers' => \App\Http\Middleware\SecurityHeaders::class, // 🆕 नयाँ
    ];
}
