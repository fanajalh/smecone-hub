<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LostAndFound;
use App\Models\Marketplace; 
use App\Models\Repository;
use App\Models\ForumThread;
use App\Models\Event;     // Pastikan model ini ada
use App\Models\Prestasi;  // Pastikan model ini ada

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $points = $user->reputation_points ?? 0;
        $level = ($points >= 100) ? 'Senior Hubber' : (($points >= 50) ? 'Active Student' : 'Newcomer');

        $myLostItemsCount = LostAndFound::where('user_id', $user->id)->count();
        $myRepositoriesCount = Repository::where('user_id', $user->id)
                            ->orWhereHas('collaborators', function($q) use ($user) {
                                $q->where('user_id', $user->id);
                            })->count();

        $myChannels = ForumThread::where('user_id', $user->id)
                        ->withCount('replies') 
                        ->take(5)
                        ->get(); 
        
        $recentMarketplace = Marketplace::with('user')->latest()->take(6)->get();
        $recentLostFounds = LostAndFound::with('user')->where('status', 'active')->latest()->take(6)->get();
        $popularRepos = Repository::withCount('stars')->orderBy('stars_count', 'desc')->take(3)->get();

        // AMBIL DATA UNTUK BANNER
        $latestEvent = Event::latest()->first();
        $latestPrestasi = Prestasi::latest()->first();

        return view('dashboard.index', compact(
            'user', 'level', 'myLostItemsCount', 'myRepositoriesCount', 
            'myChannels', 'recentMarketplace', 'recentLostFounds', 'popularRepos',
            'latestEvent', 'latestPrestasi'
        ));
    }
}