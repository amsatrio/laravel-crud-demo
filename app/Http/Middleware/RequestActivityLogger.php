<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestActivityLogger
{
    /**
     * Handle an incoming request.
     * The handle method should just pass the request to the next middleware.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // We capture the start time immediately before the request proceeds.
        // We use the LARAVEL_START constant for the best overall app time.
        // If not using LARAVEL_START, you'd save microtime(true) here.
        return $next($request);
    }

    /**
     * Perform any final actions for the request.
     * This runs AFTER the response is sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        // The LARAVEL_START constant is set very early in public/index.php.
        // microtime(true) - LARAVEL_START gives the total application execution time.
        $responseTimeMs = number_format((microtime(true) - LARAVEL_START) * 1000, 2);

        $logData = [
            'method' => $request->method(),
            'url' => $request->getRequestUri(),
            'status' => $response->getStatusCode(),
            'ip' => $request->ip(),
            'duration' => $responseTimeMs . 'ms',
            // Log user ID if authenticated
            'user_id' => $request->user()->id ?? 'Guest',
        ];

        // You can log the data using Monolog's context feature
        Log::info('HTTP Request Handled', $logData);
    }
}