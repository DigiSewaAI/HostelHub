<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // Debug logging
        Log::info("CheckPermission called for: {$permission}", [
            'user_id' => Auth::id(),
            'url' => $request->url()
        ]);

        if (!Auth::check()) {
            Log::warning("User not authenticated");
            return redirect()->route('login')->with('error', 'कृपया लगइन गर्नुहोस्।');
        }

        $user = Auth::user();

        // Debug user info
        Log::info("User check:", [
            'user_id' => $user->id,
            'roles' => $user->getRoleNames()->toArray(),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'can_' . $permission => $user->can($permission)
        ]);

        // Admin लाई सबै permission
        if ($user->hasRole('admin')) {
            Log::info("User is admin, allowing access");
            return $next($request);
        }

        // Check permission - ✅ FIXED: Check both formats
        $hasPermission = $user->can($permission) ||
            $user->can(str_replace('.', '_', $permission)) ||
            $user->can(str_replace('view', 'access', $permission));

        if (!$hasPermission) {
            Log::warning("User does not have permission: {$permission}", [
                'user_id' => $user->id,
                'user_roles' => $user->getRoleNames()->toArray(),
                'user_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                'checked_permission' => $permission,
                'alternative_1' => str_replace('.', '_', $permission),
                'alternative_2' => str_replace('view', 'access', $permission)
            ]);
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन।');
        }

        Log::info("User has permission: {$permission}, allowing access");
        return $next($request);
    }
}
