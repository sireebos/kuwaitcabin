<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceptionistOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Allow admin and receptionist
        // Check if user has any of the allowed roles
        $roleNames = $user->roles->pluck('name')->toArray();
        $allowedRoles = ['admin', 'manager', 'receptionist'];
        
        if ($user->isAdmin() || array_intersect($allowedRoles, $roleNames) || $user->role?->name === 'receptionist' || $user->role?->name === 'manager') {
            return $next($request);
        }

        abort(403, 'Unauthorized. Only receptionists can access this resource.');
    }
}

