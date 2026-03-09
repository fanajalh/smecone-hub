<?php

namespace App\Http\Controllers;

use App\Models\LostAndFound;
use App\Models\User;
use App\Models\ItemMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LostAndFoundController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');

        $items = LostAndFound::with('user')
            ->where('status', 'active') 
            ->when($search, function($query, $search) {
                return $query->where('item_name', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->latest()
            ->get();

        return view('lost-found.index', compact('items', 'search', 'type'));
    }

    public function create()
    {
        return view('lost-found.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('lost_and_founds', 'public');
        }

        LostAndFound::create([
            'user_id' => auth()->id(),
            'item_name' => $request->item_name,
            'type' => $request->type,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => 'pending', 
        ]);

        return redirect('/lost-found')->with('success', 'Laporan berhasil dibuat! Menunggu verifikasi dari Admin Kesiswaan agar dapat ditampilkan.');
    }

    public function show($id)
    {
        $item = LostAndFound::with('user')->findOrFail($id);
        return view('lost-found.show', compact('item'));
    }

    public function edit($id)
    {
        $item = LostAndFound::findOrFail($id);
        if ($item->user_id !== auth()->id() && !auth()->user()->is_admin) abort(403, 'Akses ditolak.');
        return view('lost-found.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = LostAndFound::findOrFail($id);
        if ($item->user_id !== auth()->id() && !auth()->user()->is_admin) abort(403, 'Akses ditolak.');

        $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) Storage::disk('public')->delete($item->image);
            $item->image = $request->file('image')->store('lost_and_founds', 'public');
        }

        $item->update([
            'item_name' => $request->item_name,
            'description' => $request->description,
        ]);

        return redirect("/lost-found/{$item->id}")->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = LostAndFound::findOrFail($id);
        if ($item->user_id !== auth()->id() && !auth()->user()->is_admin) abort(403, 'Akses ditolak.');

        if ($item->image) Storage::disk('public')->delete($item->image);
        $item->delete();

        if(auth()->user()->is_admin) return redirect('/admin')->with('success', 'Laporan telah dihapus.');
        return redirect('/lost-found')->with('success', 'Laporan berhasil dihapus.');
    }

    public function confirmAdmin($id)
    {
        $item = LostAndFound::findOrFail($id);
        if (!auth()->user()->is_admin) abort(403, 'Hanya Kesiswaan yang dapat mengonfirmasi.');

        $item->update(['status' => 'active']);
        
        if ($item->type == 'found') {
            $penemu = User::find($item->user_id);
            $penemu->increment('reputation_points', 15);
            return back()->with('success', 'Laporan dipublikasikan! +15 Poin diberikan ke siswa pelapor.');
        }

        return back()->with('success', 'Laporan kehilangan berhasil dipublikasikan!');
    }

    public function resolve(Request $request, $id)
    {
        $item = LostAndFound::findOrFail($id);
        $item->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id()
        ]);
        
        if(auth()->user()->is_admin) return redirect('/admin')->with('success', 'Kasus selesai. Barang ditarik dari halaman publik.');
        return back()->with('success', 'Status laporan diubah menjadi Selesai.');
    }

    // ==========================================
    // FITUR INTERNAL CHAT (ADMIN & SISWA)
    // ==========================================
    public function chatRoom($id)
    {
        $item = LostAndFound::findOrFail($id);
        
        if (!auth()->user()->is_admin && $item->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke ruang obrolan ini.');
        }

        $chats = ItemMessage::with('user')->where('lost_and_found_id', $id)->oldest()->get();
        return view('lost-found.chat', compact('item', 'chats'));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $chat = ItemMessage::create([
            'lost_and_found_id' => $id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'chat' => $chat->load('user')
            ]);
        }
        return back();
    }

    public function fetchMessages(Request $request, $id)
    {
        $lastId = $request->query('last_id', 0);
        $chats = ItemMessage::with('user')
                    ->where('lost_and_found_id', $id)
                    ->where('id', '>', $lastId)
                    ->oldest()
                    ->get();

        return response()->json($chats);
    }
}