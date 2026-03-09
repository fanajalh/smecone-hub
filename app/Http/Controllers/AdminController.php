<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LostAndFound;

class AdminController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) abort(403, 'Akses Ditolak.');

        $totalPending = LostAndFound::where('status', 'pending')->count();
        $totalActive = LostAndFound::where('status', 'active')->count();
        $totalResolved = LostAndFound::where('status', 'resolved')->count();

        // Pisahkan menjadi dua daftar untuk Panel Admin
        $pendingItems = LostAndFound::with('user')->where('status', 'pending')->latest()->get();
        $activeItems = LostAndFound::with('user')->where('status', 'active')->latest()->get();

        return view('admin.index', compact('totalPending', 'totalActive', 'totalResolved', 'pendingItems', 'activeItems'));
    }
}