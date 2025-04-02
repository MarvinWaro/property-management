<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TrackAssetsMode;
use App\Http\Middleware\RoleMiddleware; // Import your RoleMiddleware

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add TrackAssetsMode to the web middleware group
        $middleware->web(append: [
            TrackAssetsMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Register the route middleware alias for 'role'
$app->router->aliasMiddleware('role', RoleMiddleware::class);

return $app;
