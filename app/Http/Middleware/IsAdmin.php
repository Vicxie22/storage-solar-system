<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Mengecek apakah user sudah login dan memiliki role Admin
        if (auth()->check() && auth()->user()->role === 'Admin') {
            return $next($request);
        }

        // Jika bukan Admin, tolak aksesnya
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}