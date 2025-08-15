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
        // If user is not authenticated, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Get the organization from session if exists
        $orgId = session('current_org_id');

        // If no org in session, get the first organization the user belongs to
        if (!$orgId) {
            $orgUser = OrganizationUser::where('user_id', $user->id)->first();
            if ($orgUser) {
                $orgId = $orgUser->org_id;
                session(['current_org_id' => $orgId]);
            }
        }

        // If still no org, redirect to organization creation
        if (!$orgId) {
            return redirect()->route('register.show');
        }

        // Get the organization
        $organization = Organization::find($orgId);

        // Check if user has access to this organization
        $hasAccess = OrganizationUser::where('user_id', $user->id)
            ->where('org_id', $orgId)
            ->exists();

        if (!$hasAccess) {
            // Clear the session org and redirect
            session()->forget('current_org_id');
            return redirect()->route('register.show');
        }

        // Set the organization in the request
        $request->attributes->set('organization', $organization);
        $request->attributes->set('org_id', $orgId);

        // Make organization available globally
        view()->share('currentOrganization', $organization);

        return $next($request);
    }
}
