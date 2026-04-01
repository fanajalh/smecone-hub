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
        
        $channels = ForumThread::with(['user', 'members'])
            ->withCount('replies')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            })
            // Tambahkan relasi join request user aktif
            ->with(['joinRequests' => function($q) {
                $q->where('user_id', auth()->id());
            }])
            ->latest()
            ->get();

        return view('forum.index', compact('channels', 'search'));
    }

    public function joinChannel($id)
    {
        $channel = ForumThread::findOrFail($id);
        
        if ($channel->is_private && !auth()->user()->is_admin && $channel->user_id !== auth()->id()) {
            // Minta Izin (Request Join)
            $existingRequest = \App\Models\ChannelRequest::where('forum_thread_id', $id)
                                ->where('user_id', auth()->id())->first();
            
            if (!$existingRequest) {
                \App\Models\ChannelRequest::create([
                    'forum_thread_id' => $channel->id,
                    'user_id' => auth()->id(),
                    'status' => 'pending'
                ]);
                return redirect('/forum')->with('success', 'Permintaan gabung terkirim! Menunggu persetujuan pembuat channel.');
            }
            return redirect('/forum')->withErrors(['error' => 'Permintaan kamu sudah dalam antrean.']);
        }

        if (!$channel->members->contains(auth()->id())) {
            $channel->members()->attach(auth()->id());
        }

        return redirect("/forum/{$id}")->with('success', 'Berhasil bergabung ke channel!');
    }

    public function joinViaInvite($code)
    {
        $channel = ForumThread::where('invite_code', $code)->firstOrFail();
        
        if (!$channel->members->contains(auth()->id())) {
            $channel->members()->attach(auth()->id());
        }

        return redirect("/forum/{$channel->id}")->with('success', 'Berhasil masuk melalui Link Invite!');
    }

    public function show($id)
    {
        $channel = ForumThread::with(['assignments.submissions.user'])->findOrFail($id);

        if (!$channel->members->contains(auth()->id())) {
            return redirect('/forum')->withErrors(['error' => 'Kamu harus bergabung ke channel ini terlebih dahulu.']);
        }

        // Ambil repository milik user login untuk pilihan pengumpulan tugas
        $myRepositories = \App\Models\Repository::where('user_id', auth()->id())->get();

        // 🔥 FIX: Wajib load relasi repliedMessage.user agar UI Reply tidak error
        $chats = ForumReply::with(['user', 'repliedMessage.user'])
                    ->where('forum_thread_id', $id)
                    ->oldest()
                    ->get();

        return view('forum.show', compact('channel', 'chats', 'myRepositories'));
    }

    // ==========================================
    // BAGIAN 2: LOGIKA CHAT (KIRIM, EDIT, HAPUS, REAKSI)
    // ==========================================

    public function storeMessage(Request $request, $id)
    {
        $request->validate([
            'content' => 'required_without_all:poll_data,media|string|nullable',
            'reply_to_id' => 'nullable|exists:forum_replies,id',
            'poll_data' => 'nullable|array',
            'media' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm',
        ]);

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $ext = strtolower($file->getClientOriginalExtension());
            $mediaType = in_array($ext, ['mp4', 'mov', 'webm']) ? 'video' : 'image';
            $mediaPath = $file->store('chat-media/' . $id, 'public');
        }

        $chat = ForumReply::create([
            'forum_thread_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'reply_to_id' => $request->reply_to_id,
            'poll_data' => $request->poll_data,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
        ]);

        return response()->json([
            'success' => true, 
            'chat' => $chat->load(['user', 'repliedMessage.user'])
        ]);
    }

    public function searchMessages(Request $request, $id)
    {
        $q = $request->query('q', '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = ForumReply::with('user')
            ->where('forum_thread_id', $id)
            ->where('content', 'like', "%{$q}%")
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn($chat) => [
                'id' => $chat->id,
                'content' => $chat->content,
                'user' => $chat->user->name,
                'time' => $chat->created_at->format('d M H:i'),
            ]);

        return response()->json($results);
    }

    public function editMessage(Request $request, $id)
    {
        $chat = ForumReply::findOrFail($id);
        
        if ($chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat->update([
            'content' => $request->content,
            'is_edited' => true
        ]);

        return response()->json(['success' => true]);
    }

    public function votePoll(Request $request, $id)
    {
        $chat = ForumReply::findOrFail($id);
        $optionToVote = $request->input('option');
        $userId = auth()->id();

        $pollData = $chat->poll_data;
        if (!$pollData || !isset($pollData['options']) || !in_array($optionToVote, $pollData['options'])) {
            return response()->json(['error' => 'Pilihan tidak valid'], 400);
        }

        // Initialize votes history if not exist
        $votes = $pollData['votes'] ?? [];
        
        // Remove current user from all other options (so they only vote once per poll)
        // Or if they click the same option, it toggles (unvotes)
        $toggled = false;
        foreach ($pollData['options'] as $opt) {
            $optVotes = $votes[$opt] ?? [];
            if (in_array($userId, $optVotes)) {
                // Remove from this option
                $votes[$opt] = array_values(array_diff($optVotes, [$userId]));
                if ($opt === $optionToVote) {
                    $toggled = true; // User un-voted
                }
            }
        }

        // If they didn't just un-vote the current option, add their vote to the new option
        if (!$toggled) {
            if (!isset($votes[$optionToVote])) {
                $votes[$optionToVote] = [];
            }
            $votes[$optionToVote][] = $userId;
        }

        $pollData['votes'] = $votes;
        $chat->update(['poll_data' => $pollData]);

        return response()->json(['success' => true, 'poll_data' => $pollData]);
    }
    
    public function deleteMessage($id)
    {
        $chat = ForumReply::findOrFail($id);
        
        // Hanya pemilik pesan atau admin yang boleh hapus
        if ($chat->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat->delete();
        return response()->json(['success' => true]);
    }

    public function reactMessage(Request $request, $id)
    {
        $chat = ForumReply::findOrFail($id);
        $emoji = $request->emoji;
        $userId = auth()->id();

        $reactions = $chat->reactions ?? [];
        
        // Logika Toggle: Kalau udah pencet emoji yang sama, hapus emojinya. Kalau belum, tambahkan.
        if (isset($reactions[$emoji]) && in_array($userId, $reactions[$emoji])) {
            $reactions[$emoji] = array_diff($reactions[$emoji], [$userId]);
            if (empty($reactions[$emoji])) {
                unset($reactions[$emoji]);
            }
        } else {
            $reactions[$emoji][] = $userId;
        }

        $chat->update(['reactions' => $reactions]);
        return response()->json(['success' => true, 'reactions' => $reactions]);
    }

    public function fetchMessages(Request $request, $id)
    {
        $lastId = $request->query('last_id', 0);
        
        // 🔥 FIX: Tambahkan load relasi agar JS Polling mendapat data yang utuh
        $chats = ForumReply::with(['user', 'repliedMessage.user'])
                    ->where('forum_thread_id', $id)
                    ->where('id', '>', $lastId)
                    ->oldest()
                    ->get();

        return response()->json($chats);
    }

    // ==========================================
    // BAGIAN 3: MANAJEMEN CHANNEL DI DASHBOARD
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

        $isPrivate = $request->has('is_private');
        $inviteCode = $isPrivate ? Str::random(10) : null;

        $thread = ForumThread::create([
            'user_id' => auth()->id(),
            'title' => $channelName,
            'content' => $request->content,
            'is_solved' => false,
            'is_private' => $isPrivate,
            'invite_code' => $inviteCode,
        ]);

        // Otomatis jadikan pembuat sebagai anggota
        $thread->members()->attach(auth()->id());

        return redirect('/dashboard')->with('success', 'Channel #' . $channelName . ' berhasil dibuat!');
    }

    public function manageChannel($id)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $allUsers = User::where('id', '!=', auth()->id())->get(); 
        
        $pendingRequests = $channel->joinRequests()->with('user')->where('status', 'pending')->get();
        
        return view('dashboard.manage-channel', compact('channel', 'allUsers', 'pendingRequests'));
    }

    public function approveRequest($id, $requestId)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $req = \App\Models\ChannelRequest::where('id', $requestId)->where('forum_thread_id', $channel->id)->firstOrFail();
        
        $req->update(['status' => 'approved']);
        if (!$channel->members->contains($req->user_id)) {
            $channel->members()->attach($req->user_id);
        }
        
        return back()->with('success', 'Persetujuan izin masuk berhasil!');
    }

    public function rejectRequest($id, $requestId)
    {
        $channel = ForumThread::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $req = \App\Models\ChannelRequest::where('id', $requestId)->where('forum_thread_id', $channel->id)->firstOrFail();
        
        $req->update(['status' => 'rejected']);
        
        return back()->with('success', 'Permintaan masuk telah ditolak.');
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