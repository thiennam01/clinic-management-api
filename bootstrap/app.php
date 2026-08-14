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
        api: __DIR__.'/../routes/api.php', // 1. Declare API routes
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register the 'permission' alias for the CheckPermission middleware
        $middleware->alias([
            'permission' => CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // API always returns JSON instead of HTML
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Standardize exception response for the API
        $exceptions->render(function (\Throwable $e, Request $request) {

            if (! $request->is('api/*')) {
                return null;
            }

            // 1. Standardize handling for Validation errors (HTTP 422) according to the requirements
            if ($e instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
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
