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
        // ... kode update kamu ...
    }
}