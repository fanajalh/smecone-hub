<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Jika yang login BUKAN admin (siswa), lempar paksa ke dashboard siswa
            if (!Auth::user()->is_admin) {
                return redirect('/dashboard');
            }

            // Jika benar admin, silakan masuk
            return $next($request);
        }

        return redirect('/login');
    }
}