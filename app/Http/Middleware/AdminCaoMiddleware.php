<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminCaoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }

        // Check if user has admin or cao role
        if (!in_array(Auth::user()->role, ['admin', 'cao'])) {
            if (Auth::user()->role == 'staff') {
                return redirect('/staff-dashboard');
            } else {
                return redirect('/login')->with('error', 'Unauthorized access.');
            }
        }

        return $next($request);
    }
}
