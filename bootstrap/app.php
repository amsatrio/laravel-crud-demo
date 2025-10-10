<?php

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // --- 1. Catch 404 Not Found Exceptions ---
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->errorResponse("data not found", 404);
            }
        });

        // --- 2. Catch ALL Other Exceptions (The Global Handler) ---
        $exceptions->render(function (Throwable $e, Request $request) {
            
            // Only apply this custom format for JSON/API requests
            if ($request->expectsJson() || $request->is('api/*')) {
                
                // Get the appropriate status code (e.g., 400, 422, 500)
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                // Determine if we should show stacktrace (e.g., only in debug mode)
                $stacktrace = config('app.debug') ? [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => collect($e->getTrace())->map(fn ($trace) => [
                        'file' => $trace['file'] ?? null,
                        'line' => $trace['line'] ?? null,
                        'function' => $trace['function'] ?? null,
                    ])->take(5)->toArray(), // show top 5 traces
                ] : [];

                return $this->errorResponse(config('app.debug') ? $e->getMessage() : 'Something went wrong.', $statusCode, $stacktrace);
            }
        });

    })->create();
