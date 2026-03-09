<?php

declare(strict_types=1);

use Illuminate\Http\Response;
use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckHeadersMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //        $middleware->api(append: [
        //            CheckHeadersMiddleware::class,
        //        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Force JSON responses for all routes starting with api/
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            return $request->is('api/*') || $request->expectsJson();
        });

        // Specialized handler for Validation Errors
        $exceptions->render(function (Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'Error',
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], $e->status);
            }
        });

        //  Global renderer for API exceptions
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                $status = method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR;

                return response()->json([
                    'status' => 'Error',
                    'message' => app()->isLocal() ? $e->getMessage() : 'Server Error',
                    'debug' => app()->isLocal() ? [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ] : null,
                ], $status);
            }
        });
    })
    ->create();
