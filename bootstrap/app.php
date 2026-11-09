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
        $middleware->trustProxies(at: '*');

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('subscribe') || $request->routeIs('subscribe')) {
                session()->flash('warning', 'Silakan login terlebih dahulu untuk subscribe.');
            } else {
                session()->flash('warning', 'Silakan login terlebih dahulu untuk melanjutkan.');
            }

            $previous = url()->previous();
            if ($previous && !str_contains($previous, '/login') && !str_contains($previous, '/register') && !str_contains($previous, '/subscribe')) {
                session()->put('url.intended', $previous);
            }

            return route('login');
        });

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
