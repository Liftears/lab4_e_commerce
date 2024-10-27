<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if(!Auth::check()) {
            return redirect('/login');
        }

        if(!Auth::user()->roles()->where('role_name', $role)->exists())
        {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        return $next($request);
    }
}
