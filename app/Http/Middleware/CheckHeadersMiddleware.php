<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation for non-API routes
        if (! str_starts_with($request->path(), 'api/')) {
            return $next($request);
        }

        // Check for required headers
        if (! $this->hasValidHeaders($request)) {
            return response()->json([
                'message' => 'Required API request headers are missing or invalid. Please include Accept with the value application/json.',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }

    private function hasValidHeaders(Request $request): bool
    {
        return $request->hasHeader('Accept')
            && $request->header('Accept') === 'application/json'
            && $request->expectsJson();
    }
}
