<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Izinkan akses jika user adalah admin ATAU owner
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner())) {
            return $next($request);
        }

        abort(403, 'ANDA TIDAK PUNYA AKSES ADMIN ATAU OWNER.');
    }
}