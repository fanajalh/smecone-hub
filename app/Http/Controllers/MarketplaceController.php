<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $products = Marketplace::with('user')
            ->when($search, function($query, $search) {
                return $query->where('item_name', 'like', "%{$search}%");
            })
            ->when($category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->latest()
            ->get();

        return view('marketplace.index', compact('products', 'search', 'category'));
    }

    public function create() { return view('marketplace.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'category' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('marketplaces', 'public');
        }

        Marketplace::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'type' => $request->type ?? 'Ready Stock',
            'location' => $request->location,
            'image' => $path,
        ]);

        return redirect('/marketplace')->with('success', 'Produk berhasil dipasang!');
    }

    public function show($id)
    {
        $product = Marketplace::with('user')->findOrFail($id);
        return view('marketplace.show', compact('product'));
    }
}