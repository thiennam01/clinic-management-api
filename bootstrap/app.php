<?php

use App\Http\Middleware\CheckPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Always render JSON for API requests instead of HTML
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Standardize exception responses for API
        $exceptions->render(function (\Throwable $e, Request $request) {

            if (! $request->is('api/*')) {
                return null;
            }

            // 1. Handle Validation Exception (HTTP 422)
            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'The given data was invalid.',
                    'errors'  => $e->errors(),
                ], 422);
            }

            // 2. Handle other HTTP status codes (401, 403, 404, 405...)
            $status = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            $message = match ($status) {
                401 => 'Unauthenticated.',
                403 => 'Forbidden.',
                404 => 'Resource not found.',
                405 => 'Method not allowed.',
                default => $status >= 500
                    ? (config('app.debug') ? $e->getMessage() : 'Server error.')
                    : ($e->getMessage() ?: 'Request failed.'),
            };

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        });

    })->create();