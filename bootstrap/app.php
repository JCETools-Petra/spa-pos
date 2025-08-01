<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan middleware alias Anda di sini
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'branch_user' => \App\Http\Middleware\IsBranchUser::class,
        ]);

        // Middleware lain yang mungkin ingin Anda daftarkan bisa ditambahkan di sini
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Menangani CSRF token mismatch (Error 419)
        $exceptions->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            // Alihkan ke URL yang Anda inginkan ketika terjadi error 419
            return redirect('https://fortunaspa.my.id');
        });
    })->create();