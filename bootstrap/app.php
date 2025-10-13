<?php

use App\Http\Middleware\RequestActivityLogger;
use App\Http\Middleware\GlobalExceptionMiddleware;
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
        $middleware->append(RequestActivityLogger::class);
        $middleware->validateCsrfTokens(except: [
            '/api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // --- 1. Catch 404 Not Found Exceptions ---
        $exceptions->render(function (NotFoundHttpException $e, Request $request){
            $globalExceptionMiddleware = new GlobalExceptionMiddleware();
            return $globalExceptionMiddleware->notFoundException($e, $request);
        });

        // --- 2. Catch ALL Other Exceptions (The Global Handler) ---
        $exceptions->render(function (Throwable $e, Request $request) {
            $globalExceptionMiddleware = new GlobalExceptionMiddleware();
            return $globalExceptionMiddleware->throwableException($e, $request);
        });

    })->create();
