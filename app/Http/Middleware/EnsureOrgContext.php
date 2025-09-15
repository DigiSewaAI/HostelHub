<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Models\OrganizationUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrgContext
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip organization check for these routes
        $exemptRoutes = [
            'login',
            'register.organization',
            'register.organization.store', // ADD THIS LINE
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'verification.notice',
            'verification.verify',
            'verification.send',
            'onboarding.index', // ADD THIS LINE
            'onboarding.store', // ADD THIS LINE
            'onboarding.skip' // ADD THIS LINE
        ];

        if (in_array($request->route()->getName(), $exemptRoutes)) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $orgId = session('current_organization_id');

        if (!$orgId) {
            $orgUser = OrganizationUser::where('user_id', $user->id)->first();
            $orgId = $orgUser->organization_id ?? null;
            if ($orgId) {
                session(['current_organization_id' => $orgId]);
            }
        }

        if (!$orgId) {
            // FIX: Check if user has organization access before redirecting
            $hasOrganization = OrganizationUser::where('user_id', $user->id)->exists();

            if ($hasOrganization) {
                // User has organization but session missing, set the first organization
                $firstOrg = OrganizationUser::where('user_id', $user->id)->first();
                session(['current_organization_id' => $firstOrg->organization_id]);
                return $next($request);
            }

            return redirect()->route('register.organization');
        }

        $organization = Organization::find($orgId);

        if (!$organization) {
            session()->forget('current_organization_id');
            return redirect()->route('register.organization');
        }

        $hasAccess = OrganizationUser::where('user_id', $user->id)
            ->where('organization_id', $orgId)
            ->exists();

        if (!$hasAccess) {
            session()->forget('current_organization_id');
            return redirect()->route('register.organization');
        }

        $request->merge(['organization' => $organization, 'organization_id' => $orgId]);
        view()->share('currentOrganization', $organization);

        return $next($request);
    }
}
