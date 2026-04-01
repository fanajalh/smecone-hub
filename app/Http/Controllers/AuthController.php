<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
                return redirect()->intended('/admin/dashboard'); 
            }

            // Jika siswa biasa
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal (Email/Password salah), lempar balik ke form login
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

        return $user->is_admin ? redirect('/admin/dashboard') : redirect('/dashboard');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ================= LUPA PASSWORD =================
    public function showForgotPassword()
    {
        if (Auth::check()) {
            return Auth::user()->is_admin ? redirect('/admin/dashboard') : redirect('/dashboard');
        }
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar di sistem kami.']);
        }

        // Hapus token lama (kalau ada) untuk email ini
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate token baru
        $token = Str::random(64);

        // Simpan token ke database
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Buat link reset password
        $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        // Kirim email reset password via Gmail SMTP
        try {
            \Illuminate\Support\Facades\Mail::to($request->email)
                ->send(new \App\Mail\ResetPasswordMail($resetLink, $user->name));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi nanti.']);
        }

        // Bonus: Kirim juga via WhatsApp Bot (kalau user punya nomor WA & bot aktif)
        if ($user->whatsapp_number) {
            try {
                Http::timeout(5)->post('http://localhost:3000/send-message', [
                    'number' => $user->whatsapp_number,
                    'message' => "🔐 *RESET PASSWORD - SMEconE Hub*\n\nHalo kak *{$user->name}*!\n\nKami menerima permintaan reset password untuk akun Anda.\n\nKlik link berikut untuk membuat password baru:\n{$resetLink}\n\n⏰ Link ini berlaku selama *60 menit*.\n\n⚠️ Jika Anda tidak merasa meminta reset password, abaikan pesan ini.\n\n_SMEconE Hub_"
                ]);
            } catch (\Exception $e) {
                // Abaikan error WA bot
            }
        }

        return back()->with('status', 'Link reset password telah dikirim ke email Anda! Silakan cek inbox atau folder spam.');
    }

    public function showResetForm(Request $request, $token)
    {
        if (Auth::check()) {
            return Auth::user()->is_admin ? redirect('/admin/dashboard') : redirect('/dashboard');
        }

        $email = $request->query('email');

        if (!$email) {
            return redirect('/forgot-password')->withErrors(['email' => 'Link reset tidak valid.']);
        }

        // Cek apakah token masih ada dan valid (belum expired - 60 menit)
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return redirect('/forgot-password')->withErrors(['email' => 'Link reset tidak valid atau sudah digunakan.']);
        }

        // Cek apakah token cocok
        if (!Hash::check($token, $resetRecord->token)) {
            return redirect('/forgot-password')->withErrors(['email' => 'Link reset tidak valid.']);
        }

        // Cek expired (60 menit)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect('/forgot-password')->withErrors(['email' => 'Link reset sudah kadaluarsa. Silakan request ulang.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Cek token di database
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah digunakan.']);
        }

        // Verifikasi token
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Token reset tidak valid.']);
        }

        // Cek expired
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token sudah kadaluarsa. Silakan request ulang.']);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token setelah dipakai
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Kirim notifikasi via WA bahwa password berhasil diubah
        if ($user->whatsapp_number) {
            try {
                Http::timeout(5)->post('http://localhost:3000/send-message', [
                    'number' => $user->whatsapp_number,
                    'message' => "✅ *PASSWORD BERHASIL DIRESET*\n\nHalo kak *{$user->name}*!\n\nPassword akun Anda di SMEconE Hub berhasil diubah pada " . now()->format('d M Y, H:i') . " WIB.\n\n⚠️ Jika Anda tidak merasa mengubah password, segera hubungi admin.\n\n_SMEconE Hub_"
                ]);
            } catch (\Exception $e) {
                // Abaikan error
            }
        }

        return redirect('/login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
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

            return $user->is_admin ? redirect('/admin/dashboard') : redirect('/dashboard');

        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google. Coba lagi.']);
        }
    }
}