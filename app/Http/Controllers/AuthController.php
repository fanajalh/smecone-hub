<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ================= LOGIN =================
    public function showLogin()
    {
        // Cegah user yang udah login buka halaman login lagi (Biar gak muter-muter)
        if (Auth::check()) {
            return Auth::user()->is_admin ? redirect('/admin/dashboard') : redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // PENGECEKAN GURU / ADMIN
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin/dashboard'); // 🔥 FIX: Arahkan ke dashboard admin yang benar
            }

            // Jika siswa biasa
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ================= REGISTER =================
    public function showRegister()
    {
        // Cegah user yang udah login buka halaman register
        if (Auth::check()) {
            return Auth::user()->is_admin ? redirect('/admin/dashboard') : redirect('/dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nis' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // LOGIKA EMAIL SAKTI GURU KESISWAAN
        $emailGuru = 'kesiswaan@smecone.com';
        $isAdmin = ($request->email === $emailGuru) ? true : false;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'password' => Hash::make($request->password),
            'is_admin' => $isAdmin,
        ]);

        Auth::login($user);

        if ($user->is_admin) {
            return redirect('/admin/dashboard'); // 🔥 FIX
        }

        return redirect('/dashboard');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ================= LOGIN WITH GOOGLE =================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $emailGuru = 'kesiswaan@smecone.com';
            $isAdmin = ($googleUser->email === $emailGuru) ? true : false;
            
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'is_admin' => $isAdmin,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'nis' => rand(100000, 999999),
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                    'is_admin' => $isAdmin,
                ]);
            }

            Auth::login($user);

            if ($user->is_admin) {
                return redirect('/admin/dashboard'); // 🔥 FIX
            }
            return redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google. Coba lagi.']);
        }
    }
}