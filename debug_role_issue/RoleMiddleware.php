<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Login check
        if (!Auth::check()) {
            session()->put('url.intended', $request->fullUrl());
            return redirect()->route('login')->with('error', 'कृपया पहिले लगइन गर्नुहोस्');
        }

        $user = Auth::user();

        // 2. Role check
        if ($this->checkUserRole($user, $role)) {
            return $next($request);
        }

        // 3. Unauthorized attempt log
        Log::warning("अनधिकृत पहुँच प्रयास", [
            'user_id'       => $user->id,
            'user_role_id'  => $user->role_id,
            'required_role' => $role,
            'url'           => $request->fullUrl()
        ]);

        abort(403, 'तपाईंसँग यो पृष्ठ हेर्ने अनुमति छैन।');
    }

    /**
     * Check user role by name or ID
     */
    private function checkUserRole($user, string $role): bool
    {
        // Relation check
        if (method_exists($user, 'roleRelation') && $user->roleRelation && $user->roleRelation->name === $role) {
            return true;
        }

        // Role ID mapping
        $roleIds = [
            'admin'          => 1,
            'hostel_manager' => 2,
            'student'        => 3
        ];

        return isset($roleIds[$role]) && $user->role_id === $roleIds[$role];
    }
}
