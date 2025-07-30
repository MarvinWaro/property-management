<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUserModeMiddleware
{
    /**
     * Handle an incoming request.
     * Prevent admin users in user mode from accessing admin functions
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If admin is in user mode, redirect them back to user dashboard
        if (session('admin_user_mode')) {
            return redirect()->route('admin.user-dashboard')
                ->with('error', 'You cannot access admin functions while in user mode. Please switch back to admin mode first.');
        }

        return $next($request);
    }
}
