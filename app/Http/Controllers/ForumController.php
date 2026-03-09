<?php

namespace App\Http\Controllers;

use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    // ==========================================
    // BAGIAN 1: EKSPLORASI FORUM (CARI & JOIN)
    // ==========================================
    
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        // Ambil semua channel, hitung pesannya, dan cek apakah mengandung kata kunci pencarian
        $channels = ForumThread::with(['user', 'members'])
            ->withCount('replies')
            ->when($search, function($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                             ->orWhere('content', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('forum.index', compact('channels', 'search'));
    }

    public function joinChannel($id)
    {
        $channel = ForumThread::findOrFail($id);
        
        // Masukkan user ke channel jika belum menjadi anggota
        if (!$channel->members->contains(auth()->id())) {
            $channel->members()->attach(auth()->id());
        }

        return redirect("/forum/{$id}")->with('success', 'Berhasil bergabung ke channel!');
    }

    public function show($id)
    {
        $channel = ForumThread::findOrFail($id);
        
        // Pastikan hanya anggota yang bisa masuk ke ruang chat
        if (!$channel->members->contains(auth()->id())) {
            return redirect('/forum')->withErrors(['error' => 'Kamu harus bergabung ke channel ini terlebih dahulu.']);
        }

        $chats = ForumReply::with('user')->where('forum_thread_id', $id)->oldest()->get();
        return view('forum.show', compact('channel', 'chats'));
    }

    // Mengirim Chat via AJAX
    public function storeMessage(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);

        $chat = ForumReply::create([
            'forum_thread_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'chat' => $chat->load('user')
            ]);
        }
        return back();
    }

    // Mengambil Pesan Baru via AJAX (Polling)
    public function fetchMessages(Request $request, $id)
    {
        $lastId = $request->query('last_id', 0);
        $chats = ForumReply::with('user')
                    ->where('forum_thread_id', $id)
                    ->where('id', '>', $lastId)
                    ->oldest()
                    ->get();

        return response()->json($chats);
    }

    // ==========================================
    // BAGIAN 2: MANAJEMEN CHANNEL DI DASHBOARD
    // ==========================================

    public function createChannel()
    {
        return view('dashboard.create-channel');
    }

    public function storeChannel(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100|unique:forum_threads,title',
            'content' => 'required|string|max:255',
        ]);

        $channelName = Str::slug($request->title); 

        $thread = ForumThread::create([
            'user_id' => auth()->id(),
            'title' => $channelName,
            'content' => $request->content,
            'is_solved' => false,
        ]);

        // Otomatis jadikan pembuat sebagai anggota
        $thread->members()->attach(auth()->id());

        return redirect('/dashboard')->with('success', 'Channel #' . $channelName . ' berhasil dibuat!');
    }

    public function manageChannel($id)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $allUsers = User::where('id', '!=', auth()->id())->get(); 
        return view('dashboard.manage-channel', compact('channel', 'allUsers'));
    }

    public function updateChannel(Request $request, $id)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $channel->update([
            'title' => Str::slug($request->title),
            'content' => $request->content,
        ]);
        return back()->with('success', 'Info channel berhasil diperbarui!');
    }

    public function deleteChannel($id)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $channel->delete();
        return redirect('/dashboard')->with('success', 'Channel berhasil dihapus.');
    }

    public function addMember(Request $request, $id)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        if (!$channel->members->contains($request->user_id)) {
            $channel->members()->attach($request->user_id);
        }
        return back()->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function removeMember($id, $userId)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $channel->members()->detach($userId);
        return back()->with('success', 'Anggota berhasil dikeluarkan.');
    }
}