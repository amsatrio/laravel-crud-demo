<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class GlobalExceptionMiddleware
{
    use ApiResponseTrait;

    public function notFoundException(NotFoundHttpException $e, Request $request)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->errorResponse('data not found', 404);
        }
    }

    public function throwableException(Throwable $e, Request $request)
    {
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
    }
}
