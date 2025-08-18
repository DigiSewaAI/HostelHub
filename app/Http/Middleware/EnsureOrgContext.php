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
            'register',
            'password.request',
            'password.email',
            'password.reset',
            'verification.notice',
            'verification.verify',
            'verification.send'
        ];

        if (in_array($request->route()->getName(), $exemptRoutes)) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $orgId = session('current_org_id');

        if (!$orgId) {
            $orgUser = OrganizationUser::where('user_id', $user->id)->first();
            $orgId = $orgUser->org_id ?? null;
            if ($orgId) {
                session(['current_org_id' => $orgId]);
            }
        }

        if (!$orgId) {
            return redirect()->route('register.organization');
        }

        $organization = Organization::find($orgId);

        if (!$organization) {
            session()->forget('current_org_id');
            return redirect()->route('register.organization');
        }

        $hasAccess = OrganizationUser::where('user_id', $user->id)
            ->where('org_id', $orgId)
            ->exists();

        if (!$hasAccess) {
            session()->forget('current_org_id');
            return redirect()->route('register.organization');
        }

        $request->merge(['organization' => $organization, 'org_id' => $orgId]);
        view()->share('currentOrganization', $organization);

        return $next($request);
    }
}
