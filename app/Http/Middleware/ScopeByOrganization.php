<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScopeByOrganization
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('owner')) {
            // owner को संस्था लिने (मानौं एउटा owner को एउटै मात्र संस्था छ)
            $organization = Auth::user()->organizations()->first();
            if ($organization) {
                session(['current_organization_id' => $organization->id]);
            }
        }
        return $next($request);
    }
}
