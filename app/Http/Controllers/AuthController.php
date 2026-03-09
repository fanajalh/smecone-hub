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
            // Jika yang login adalah guru kesiswaan, arahkan langsung ke Panel Admin
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin');
            }

            // Jika siswa biasa, arahkan ke Dashboard
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ================= REGISTER =================
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nis' => ['required', 'string', 'unique:users'], // Bisa juga diisi NIP untuk guru
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // LOGIKA EMAIL SAKTI GURU KESISWAAN
        // Ubah email ini sesuai dengan email asli Bapak/Ibu guru Kesiswaan nantinya
        $emailGuru = 'kesiswaan@smecone.com';
        $isAdmin = ($request->email === $emailGuru) ? true : false;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'password' => Hash::make($request->password),
            'is_admin' => $isAdmin, // Otomatis jadi admin jika emailnya cocok
        ]);

        Auth::login($user);

        // Arahkan sesuai peran setelah register
        if ($user->is_admin) {
            return redirect('/admin');
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
            
            // Logika Email Sakti untuk Login via Google
            $emailGuru = 'kesiswaan@smecone.com';
            $isAdmin = ($googleUser->email === $emailGuru) ? true : false;
            
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'is_admin' => $isAdmin, // Pastikan status admin tetap update
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'nis' => rand(100000, 999999), // NIS Acak sementara untuk Google Login
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                    'is_admin' => $isAdmin,
                ]);
            }

            Auth::login($user);

            // Arahkan sesuai peran
            if ($user->is_admin) {
                return redirect('/admin');
            }
            return redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google. Coba lagi.']);
        }
    }
}