<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // role_id म्यापिङ (तपाईंको प्रोजेक्ट अनुसार)
        $roleMap = [
            'admin' => 1,    // role_id = 1
            'owner' => 2,    // role_id = 2 (hostel_manager)
            'student' => 3   // role_id = 3
        ];

        // यदि role म्यापमा छैन भने, सीधा role_id जाँच गर्ने
        $requiredRoleId = $roleMap[$role] ?? $role;

        // Admin (role_id=1) लाई सबै रूटमा प्रवेश दिने
        if ($user->role_id == 1) {
            return $next($request);
        }

        // Required role_id जाँच गर्ने
        if ($user->role_id != $requiredRoleId) {
            abort(403, 'तपाईंलाई यो पृष्ठ हेर्न अनुमति छैन।');
        }

        return $next($request);
    }
}
