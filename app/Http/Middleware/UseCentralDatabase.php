<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UseCentralDatabase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // For password reset routes, use central database
        if (
            $request->is('forgot-password') ||
            $request->is('reset-password') ||
            $request->is('password/*')
        ) {
            config(['database.default' => 'mysql']);
        }

        return $next($request);
    }
}
