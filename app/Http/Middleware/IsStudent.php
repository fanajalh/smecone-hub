<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau belum login, lempar ke login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // 🔥 FIX 3: Kalau dia ternyata ADMIN, jangan lempar balik ke "/", tapi ke area admin
        if (auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }

        // Kalau bukan admin (berarti student), izinkan lewat
        return $next($request);
    }
}