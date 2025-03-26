<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackAssetsMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->routeIs(['assets.dashboard', 'property.*', 'end_users.*'])) {
            session(['from_assets_mode' => true]);
        } elseif ($request->routeIs('dashboard')) {
            session(['from_assets_mode' => false]);
        }

        return $next($request);
    }
}
