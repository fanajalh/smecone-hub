<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Jika yang login ternyata ADMIN, lempar paksa ke dashboard admin
            if (Auth::user()->is_admin) {
                return redirect('/admin/dashboard');
            }
            
            // Jika benar siswa, silakan masuk
            return $next($request);
        }

        return redirect('/login');
    }
}