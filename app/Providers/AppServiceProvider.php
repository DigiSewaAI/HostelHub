<?php

namespace App\Providers;

use App\Services\PlanLimitService;
use App\Services\ImageOptimizer;
use App\Services\ClassicImageOptimizer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\Models\MessageThreadParticipant;
use Illuminate\Support\Facades\Auth;

// View Components
use App\View\Components\AdminNavLink;

// Models
use App\Models\StudentDocument;
use App\Models\Student;
use App\Models\Hostel;
use App\Models\Room;

// Policies
use App\Policies\DocumentPolicy;

// Observers
use App\Observers\StudentObserver;
use App\Observers\HostelObserver;
use App\Observers\RoomObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ✅ CRITICAL FIX: Force load helper functions
        $helperFile = app_path('Helpers/helpers.php');
        if (file_exists($helperFile)) {
            require_once $helperFile;
        }

        // ✅ NEW: Register NotificationHelper (if not already in composer autoload)
        $notificationHelper = app_path('Helpers/NotificationHelper.php');
        if (file_exists($notificationHelper)) {
            require_once $notificationHelper;
        }

        // Register PlanLimitService as a singleton
        $this->app->singleton(PlanLimitService::class, function ($app) {
            return new PlanLimitService();
        });

        // Register ImageOptimizer Service as a singleton
        $this->app->singleton(ImageOptimizer::class, function ($app) {
            return new ImageOptimizer();
        });

        // Register ClassicImageOptimizer Service as a singleton
        $this->app->singleton(ClassicImageOptimizer::class, function ($app) {
            return new ClassicImageOptimizer();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Register Observers
        Student::observe(StudentObserver::class);
        Hostel::observe(HostelObserver::class);
        Room::observe(RoomObserver::class);

        // ✅ Register Policies
        Gate::policy(StudentDocument::class, DocumentPolicy::class);

        // Register Blade component
        Blade::component('admin-nav-link', AdminNavLink::class);

        // Register custom middleware
        $this->app['router']->aliasMiddleware('hasOrganization', \App\Http\Middleware\HasOrganization::class);
        $this->app['router']->aliasMiddleware('enforce.plan.limits', \App\Http\Middleware\EnforcePlanLimits::class);
        $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);

        // ✅ [EXISTING] View Composer for Owner Layout – passes message unread count (using $unreadCount)
        View::composer('owner', function ($view) {
            $unreadCount = 0;
            if (Auth::check()) {
                $userId = Auth::id();
                // Adjust query as per your database structure
                $unreadCount = MessageThreadParticipant::where('user_id', $userId)
                    ->whereColumn('last_read_at', '<', 'thread.last_message_at')
                    ->count();
            }
            $view->with('unreadCount', $unreadCount);
        });

        // ✅ [NEW] Global View Composer for Notifications – passes notifications and notificationUnreadCount
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $notifications = $user->notifications()->latest()->take(10)->get();
                $notificationUnreadCount = $user->unreadNotifications()->count();
                $view->with(compact('notifications', 'notificationUnreadCount'));
            } else {
                $view->with('notifications', collect([]))->with('notificationUnreadCount', 0);
            }
        });

        // Additional bootstrapping code can go here
        // $this->shareCommonViewData();
    }

    /**
     * Share common data with all views (optional method)
     */
    protected function shareCommonViewData(): void
    {
        // Example: Share app name with all views
        // view()->share('appName', config('app.name'));
    }
}
