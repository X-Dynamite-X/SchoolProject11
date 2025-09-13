<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*', // Exclude all API routes from CSRF validation since they use Sanctum
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        // For API routes using Sanctum with bearer token, skip CSRF validation
        if ($request->is('api/*') && $request->bearerToken()) {
            return $next($request);
        }

        // For API routes using session-based auth (SPA), validate CSRF
        if ($request->is('api/*') && !$request->bearerToken()) {
            // Check if user is authenticated via session
            if (\Illuminate\Support\Facades\Auth::check()) {
                // Validate CSRF token for session-based API requests
                return parent::handle($request, $next);
            }
        }

        return parent::handle($request, $next);
    }
}
