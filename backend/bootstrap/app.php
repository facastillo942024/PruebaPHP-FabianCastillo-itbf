<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // La API es stateless: siempre negocia JSON aunque el cliente no envíe
        // la cabecera Accept, evitando redirecciones o respuestas HTML.
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Garantiza que cualquier error en rutas /api/* se renderice como JSON.
        $exceptions->shouldRenderJsonWhen(function ($request, $throwable) {
            return $request->is('api/*') || $request->expectsJson();
        });
    })->create();
