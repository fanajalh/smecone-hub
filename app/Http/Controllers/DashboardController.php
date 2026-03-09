<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MarketplaceItem;
use App\Models\LostAndFound;
use App\Models\ForumThread; // Tambahkan ini untuk memanggil model Channel

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $level = 'Pemula';
        if ($user->reputation_points > 50) $level = 'Aktif';
        if ($user->reputation_points > 150) $level = 'Bintang Sekolah';

        // Ambil data untuk slider Halaman Utama
        $recentMarketplace = MarketplaceItem::with('user')->latest()->take(5)->get();
        $recentLostFounds = LostAndFound::with('user')->where('status', 'active')->latest()->take(5)->get();

        // AMBIL DATA CHANNEL MILIK USER INI SAJA (Sebagai Admin)
        $myChannels = ForumThread::where('user_id', $user->id)->withCount('replies')->latest()->get();

        return view('dashboard.index', compact('user', 'level', 'recentMarketplace', 'recentLostFounds', 'myChannels'));
    }
}