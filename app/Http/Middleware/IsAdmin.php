<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user is an admin
        $user = $request->user();

        if (!$user || !$user->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Admins only.');
        }

        return $next($request);
    }
}