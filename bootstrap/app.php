<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::any('/tus-upload/{any?}', function () {
                return app('tus-server')->serve();
            })->where('any', '.*')->name('tus-upload');
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
            'http://localhost:8000/tus-upload/*',
            'http://127.0.0.1:8000/tus-upload/*',
        ]);

        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class,
            'irban' => \App\Http\Middleware\Irban::class,
        ]);
    })
    ->withProviders([
        App\Providers\TusServiceProvider::class,
    ], true)
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
