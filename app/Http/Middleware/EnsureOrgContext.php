<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Organization;
use App\Models\OrganizationUser;

class EnsureOrgContext
{
    public function handle(Request $request, Closure $next)
    {
        // Routes to skip (login/register/password etc.)
        $exemptRoutes = [
            'login', 'register', 'register.organization', 'register.organization.store',
            'password.request', 'password.email', 'password.reset',
            'verification.notice', 'verification.verify', 'verification.send',
            'onboarding.index', 'onboarding.store', 'onboarding.skip'
        ];

        if (in_array(optional($request->route())->getName(), $exemptRoutes)) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // STEP 1: Set tenant context if not already in session
        if (!Session::has('current_organization_id')) {
            if ($user->hasRole('admin')) {
                $firstOrg = $user->organizations()->first();
                if ($firstOrg) {
                    Session::put('current_organization_id', $firstOrg->id);
                }
            } elseif ($user->hasRole('hostel_manager') || $user->hasRole('owner')) {
                $organization = $user->organizations()->first();
                if ($organization) {
                    Session::put('current_organization_id', $organization->id);
                }
            } elseif ($user->hasRole('student') && $user->student) {
                Session::put('current_organization_id', $user->student->organization_id);
            }
        }

        // STEP 2: Validate that the organization exists and user has access
        $orgId = session('current_organization_id');
        if (!$orgId) {
            $orgUser = OrganizationUser::where('user_id', $user->id)->first();
            if ($orgUser) {
                $orgId = $orgUser->organization_id;
                session(['current_organization_id' => $orgId]);
            } else {
                return redirect()->route('register.organization');
            }
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

        // STEP 3: Share organization globally for views & requests
        $request->merge(['organization' => $organization, 'organization_id' => $orgId]);
        view()->share('currentOrganization', $organization);

        return $next($request);
    }
}
