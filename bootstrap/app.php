<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // WAJIB untuk session Login agar session() bisa digunakan di Blade!
        $middleware->web(append: [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);

        // Middleware custom kamu
        $middleware->alias([
            'checkLogin' => \App\Http\Middleware\CheckLogin::class,
            'role'       => \App\Http\Middleware\RoleMiddleware::class,
            'guest.only' => \App\Http\Middleware\RedirectIfAuthenticated::class, // ADD THIS
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {})
    ->create();
