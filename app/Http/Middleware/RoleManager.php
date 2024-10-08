<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $authUserRole = Auth::user()->role;

        // Admins (role 0) can access both admin and user pages
        if ($authUserRole == 0) {
            // Admins can access both admin and user routes
            return $next($request);
        }

        // For regular users (role 1), restrict access to user pages only
        switch ($role) {
            case 'admin':
                // Only allow admins to access admin routes
                if ($authUserRole == 0) {
                    return $next($request);
                }
                break;
            case 'user':
                // Only allow users to access user routes
                if ($authUserRole == 1) {
                    return $next($request);
                }
                break;
        }

        // If user tries to access an unauthorized page, redirect based on role
        if ($authUserRole == 0) {
            return redirect()->route('admin');
        } elseif ($authUserRole == 1) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login'); // Default redirection for any other case
    }
}
