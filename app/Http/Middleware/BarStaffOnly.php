<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BarStaffOnly
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
        $roleName = $user->role?->name;
        $roleNames = $user->roles->pluck('name')->toArray();

        // Allow admin, manager, bar_manager, and bartender
        $allowedRoles = ['admin', 'manager', 'bar_manager', 'bartender'];
        if ($user->isAdmin() || array_intersect($allowedRoles, $roleNames) || in_array($roleName, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized. Only bar staff can access this resource.');
    }
}

