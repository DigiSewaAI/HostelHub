<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // यदि प्रयोगकर्ता लगइन गरिएको छैन भने लगइन पेजमा पठाउनुहोस्
        if (!Auth::check()) {
            return redirect('login');
        }

        // यदि प्रयोगकर्ताको रोल admin होइन भने होमपेजमा पठाउनुहोस्
        if (Auth::user()->role_id != 1) {
            return redirect('/');
        }

        return $next($request);
    }
}
