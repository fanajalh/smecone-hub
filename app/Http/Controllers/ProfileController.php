<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Tambahkan method ini!
    public function index()
    {
        // Pastikan kamu punya file blade untuk profile (misal: resources/views/profile/index.blade.php)
        return view('profile.index'); 
    }

    // Mungkin kamu sudah punya method update di bawahnya, biarkan saja.
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'whatsapp_number' => 'nullable|string|max:20',
            'store_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Maks 5MB
        ]);

        $data = $request->except(['avatar']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Illuminate\Support\Facades\Storage::exists('public/' . $user->avatar)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}