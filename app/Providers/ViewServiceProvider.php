<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // owner layout मा notifications र unreadCount उपलब्ध गराउने view composer
        View::composer('layouts.owner', function ($view) {
            $user = auth()->user();
            $notifications = collect();
            $unreadCount = 0;

            if ($user) {
                // पछिल्लो 10 वटा सूचनाहरू (पढिएको र नपढिएको) ड्रपडाउनका लागि लिने
                $notifications = $user->notifications()
                    ->latest()
                    ->take(10)
                    ->get();

                // ब्याजमा देखाउन केवल अपठित गणना
                $unreadCount = $user->unreadNotifications()->count();
            }

            $view->with(compact('notifications', 'unreadCount'));
        });
    }
}
