<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MarketplaceController extends Controller
{
    public function index()
    {
        $items = MarketplaceItem::with('user')->latest()->get();
        return view('marketplace.index', compact('items'));
    }

    public function create()
    {
        return view('marketplace.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|in:barang,jasa',
            'whatsapp_number' => 'required|string',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('marketplace', 'public');
        }

        $item = MarketplaceItem::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'price' => $request->price,
            'type' => $request->type,
            'whatsapp_number' => $request->whatsapp_number,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        // Trigger Bot WA
        try {
            $pesanPromosi = "*PROMOSI SMECONE HUB*\n\n" . 
                            "Ada " . $item->type . " baru dari " . auth()->user()->name . "!\n" .
                            "*" . $item->title . "*\n" .
                            "Harga: Rp" . number_format($item->price, 0, ',', '.') . "\n" .
                            "Hubungi: wa.me/" . $item->whatsapp_number;

            // Ganti URL ini dengan URL Webhook Bot WA milikmu
            Http::post('http://localhost:3000/send-group', [
                'message' => $pesanPromosi
            ]);
        } catch (\Exception $e) {
            // Lanjutkan eksekusi meskipun Bot WA sedang offline
        }

        return redirect('/marketplace')->with('success', 'Dagangan berhasil diposting!');
    }
}