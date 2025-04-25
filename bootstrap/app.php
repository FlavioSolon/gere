<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\EnsureIsOffice;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Mantém os middlewares padrão do Breeze/Inertia no grupo 'web'
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Define o grupo 'admin' com o middleware EnsureIsAdmin
        $middleware->appendToGroup('admin', [
            EnsureIsAdmin::class,
        ]);

        // Define o grupo 'office' com o middleware EnsureIsOffice
        $middleware->appendToGroup('office', [
            EnsureIsOffice::class,
        ]);

        // Adiciona aliases para facilitar o uso
        $middleware->alias([
            'admin' => EnsureIsAdmin::class,
            'office' => EnsureIsOffice::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
