<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau belum login, lempar ke login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Kalau dia benar-benar ADMIN, izinkan lewat
        if (auth()->user()->is_admin) {
            return $next($request);
        }

        // 🔥 FIX 4: Kalau dia STUDENT nyasar ke sini, lempar ke dashboard student
        return redirect('/dashboard');
    }
}