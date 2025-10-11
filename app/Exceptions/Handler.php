<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // ✅ ADD THIS: Log all AuthorizationExceptions
        $this->renderable(function (AuthorizationException $e, $request) {
            Log::error('AuthorizationException caught in Handler', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'user_roles' => auth()->user() ? auth()->user()->getRoleNames()->toArray() : null,
                'route' => $request->path(),
                'method' => $request->method(),
                'session_org' => session('current_organization_id'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Let Laravel return the usual 403 response
            return null;
        });
    }
}
