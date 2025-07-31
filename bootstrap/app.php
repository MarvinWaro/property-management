<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TrackAssetsMode;
use App\Http\Middleware\RoleMiddleware; // Import your RoleMiddleware
use App\Http\Middleware\AdminCaoMiddleware; // Import your AdminCaoMiddleware
use App\Http\Middleware\AdminUserModeMiddleware; // Import the new middleware

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

// Register the route middleware aliases
$app->router->aliasMiddleware('role', RoleMiddleware::class);
$app->router->aliasMiddleware('admin-cao', AdminCaoMiddleware::class);
$app->router->aliasMiddleware('admin.not-user-mode', AdminUserModeMiddleware::class); // Add this line

return $app;
