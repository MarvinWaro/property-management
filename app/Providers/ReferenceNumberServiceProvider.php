<?php

namespace App\Providers;

use App\Services\ReferenceNumberService;
use Illuminate\Support\ServiceProvider;

class ReferenceNumberServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ReferenceNumberService::class, function ($app) {
            return new ReferenceNumberService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
