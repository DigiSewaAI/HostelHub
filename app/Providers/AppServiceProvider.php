<?php

namespace App\Providers;

use App\Services\PlanLimitService;
use App\Services\ImageOptimizer; // ✅ ADDED: For image optimization service
use App\Services\ClassicImageOptimizer; // ✅ NEW: Classic theme image optimizer
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

// View Components
use App\View\Components\AdminNavLink;

// Models
use App\Models\StudentDocument; // ✅ ADDED: For policy registration
use App\Models\Student; // ✅ ADDED: For observer registration
use App\Models\Hostel; // ✅ ADDED: For Hostel observer registration
use App\Models\Room; // ✅ ADDED: For Room observer registration

// Policies
use App\Policies\DocumentPolicy; // ✅ ADDED: For policy registration

// Observers
use App\Observers\StudentObserver; // ✅ ADDED: For observer registration
use App\Observers\HostelObserver; // ✅ ADDED: For Hostel observer registration
use App\Observers\RoomObserver; // ✅ ADDED: For Room observer registration

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

        // ✅ ADDED: Register ImageOptimizer Service as a singleton
        $this->app->singleton(ImageOptimizer::class, function ($app) {
            return new ImageOptimizer();
        });

        // ✅ NEW: Register ClassicImageOptimizer Service as a singleton
        $this->app->singleton(ClassicImageOptimizer::class, function ($app) {
            return new ClassicImageOptimizer();
        });

        // Register other services if needed
        // $this->app->singleton(OtherService::class, function ($app) {
        //     return new OtherService();
        // });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ ADDED: Register Student Observer for automatic room occupancy updates
        Student::observe(StudentObserver::class);

        // ✅ ADDED: Register Hostel Observer
        Hostel::observe(HostelObserver::class);

        // ✅ ADDED: Register Room Observer
        Room::observe(RoomObserver::class);

        // ✅ ADDED: Register Document Policy
        Gate::policy(StudentDocument::class, DocumentPolicy::class);

        // Register Blade component
        Blade::component('admin-nav-link', AdminNavLink::class);

        // Register custom middleware
        $this->app['router']->aliasMiddleware('hasOrganization', \App\Http\Middleware\HasOrganization::class);
        $this->app['router']->aliasMiddleware('enforce.plan.limits', \App\Http\Middleware\EnforcePlanLimits::class);
        $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);

        // Additional bootstrapping code can go here
        // For example, view composers, database macros, etc.

        // Share common data with all views (optional)
        // $this->shareCommonViewData();
    }

    /**
     * Share common data with all views (optional method)
     */
    protected function shareCommonViewData(): void
    {
        // Example: Share app name with all views
        // view()->share('appName', config('app.name'));

        // Example: Share current user with all views (if authenticated)
        // view()->composer('*', function ($view) {
        //     $view->with('currentUser', auth()->user());
        // });
    }
}
