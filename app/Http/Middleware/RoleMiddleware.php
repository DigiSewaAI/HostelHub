<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. लगइन गरिएको छ कि जाँच गर्नुहोस्
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'कृपया यो पृष्ठ पहुँच गर्न लगइन गर्नुहोस्।');
        }

        $user = Auth::user();
        $roles = explode(',', $role);

        // 2. Super Admin ले सबै routes हेर्न सक्ने
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // 3. Role जाँच गर्नुहोस् (Spatie Permission सँग)
        try {
            if (!$user->hasAnyRole($roles)) {
                // 4. Role अनुसार सही error message देखाउनुहोस्
                $requiredRoles = implode(' वा ', $roles);

                // 5. User को role अनुसार सही ड्यासबोर्डमा redirect गर्नुहोस्
                if ($user->hasRole('hostel_manager')) {
                    return redirect()->route('owner.dashboard')->with('error', "तपाईंसँग यो पृष्ठ पहुँच गर्ने अनुमति छैन। यो पृष्ठ पहुँच गर्न '{$requiredRoles}' भूमिका आवश्यक छ।");
                } elseif ($user->hasRole('student')) {
                    return redirect()->route('student.dashboard')->with('error', "तपाईंसँग यो पृष्ठ पहुँच गर्ने अनुमति छैन। यो पृष्ठ पहुँच गर्न '{$requiredRoles}' भूमिका आवश्यक छ।");
                }

                return redirect()->route('dashboard')->with('error', "तपाईंसँग यो पृष्ठ पहुँच गर्ने अनुमति छैन। यो पृष्ठ पहुँच गर्न '{$requiredRoles}' भूमिका आवश्यक छ।");
            }
        } catch (RoleDoesNotExist $e) {
            // 6. Role configuration error handling
            Log::error("Role check failed: " . $e->getMessage());
            abort(500, 'भूमिका कन्फिगरेसन त्रुटि');
        }

        return $next($request);
    }
}
