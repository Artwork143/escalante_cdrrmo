<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and their role is 0 (admin)
        if (Auth::check() && Auth::user()->role === 0) {
            // Allow the request to proceed if the user is an admin
            return $next($request);
        }

        // Redirect the user to the home page or display an error if they're not an admin
        return redirect('/medical_case')->with('error', 'You do not have access to this page.');
    }
}
