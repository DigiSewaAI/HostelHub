<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEligibleForNetwork
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Admins always have access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user owns at least one eligible hostel
        $hasEligible = $user->hostels()
            ->where('status', 'active')
            ->where('is_published', true)
            ->exists();

        if (!$hasEligible) {
            return redirect()->route('owner.dashboard')
                ->with('error', 'नेटवर्क सुविधा प्रयोग गर्न तपाईंसँग सक्रिय र प्रकाशित होस्टल हुनुपर्छ।');
        }

        return $next($request);
    }
}
