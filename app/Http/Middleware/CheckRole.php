<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $roleMap = [
            'citizen' => 1,
            'worker' => 2,
            'admin' => 4,
        ];

        $requiredRole = $roleMap[$role] ?? null;

        if (!$requiredRole || auth()->user()->role_id !== $requiredRole) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
