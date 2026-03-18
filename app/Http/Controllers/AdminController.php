<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Data Dummy / Query sementara agar dashboard tidak error
        $pendingItems = collect([]); 
        $activeItems = collect([]);
        $totalPending = 0;
        $totalActive = 0;
        $totalResolved = 0;

        $totalPrestasi = 0;
        $totalEvent = 0;
        $prestasis = [];
        $events = [];

        return view('admin.dashboard', compact(
            'pendingItems', 
            'activeItems', 
            'totalPending', 
            'totalActive', 
            'totalResolved',
            'totalPrestasi',
            'totalEvent',
            'prestasis',
            'events'
        ));
    }
}