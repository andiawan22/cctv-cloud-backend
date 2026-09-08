<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Mengecek apakah role user sesuai.
     *
     * Contoh:
     * role:admin
     * role:user
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Belum login
        if (!auth()->check()) {
            abort(403);
        }

        // Role tidak sesuai
        if (auth()->user()->role != $role) {
            abort(403);
        }

        return $next($request);
    }
}