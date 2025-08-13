<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class ValidateSignature
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $relative = null): Response
    {
        if (! $request->hasValidSignature($relative !== 'relative')) {
            throw new InvalidSignatureException;
        }

        return $next($request);
    }
}
