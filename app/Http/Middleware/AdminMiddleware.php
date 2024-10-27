<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasRole('Admin')) {
            return redirect('/')->with('error', 'Unauthorized access.'); // Redirect with an error message
        }

        return $next($request);
    }
}
