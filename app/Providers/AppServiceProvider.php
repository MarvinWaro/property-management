<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\RisSlip;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share pending RIS count with all views
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                $pendingRisCount = RisSlip::where('status', 'draft')->count();
                $view->with('pendingRisCount', $pendingRisCount);
            }
        });
    }
}
