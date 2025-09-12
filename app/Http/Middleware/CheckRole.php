<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! in_array(auth()->user()->role, $roles)) {
            abort(403, 'तपाईंसँग यो कार्य गर्ने अनुमति छैन');
        }

        return $next($request);
    }
}
