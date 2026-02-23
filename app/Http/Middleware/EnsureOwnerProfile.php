<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOwnerProfile
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user()->ownerNetworkProfile) {
            return redirect()->route('network.profile.edit')
                ->with('warning', 'कृपया पहिले आफ्नो मालिक प्रोफाइल बनाउनुहोस्।');
        }

        return $next($request);
    }
}
