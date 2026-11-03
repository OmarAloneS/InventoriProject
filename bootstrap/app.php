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
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->trustProxies(at: '*');
        
        // Registramos tu middleware de bloqueo en el grupo web de manera global
        $middleware->web(append: [
            \App\Http\Middleware\CheckArcoBlocked::class,
        ]);

        // Mantenemos tu alias de roles existente
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();