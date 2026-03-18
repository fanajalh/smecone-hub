<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Comment;

class InteractionController extends Controller
{
    public function toggleLike(Request $request)
    {
        $user_id = auth()->id();
        $type = $request->type;
        $id = $request->id;

        $existingLike = Like::where('user_id', $user_id)
            ->where('likeable_type', $type)
            ->where('likeable_id', $id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $isLiked = false;
        } else {
            Like::create(['user_id' => $user_id, 'likeable_type' => $type, 'likeable_id' => $id]);
            $isLiked = true;
        }

        $likeCount = Like::where('likeable_type', $type)->where('likeable_id', $id)->count();

        // Cek apakah request datang dari JavaScript (AJAX)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'isLiked' => $isLiked,
                'likeCount' => $likeCount
            ]);
        }

        // Kalau JavaScript gagal/tidak jalan, kembalikan ke halaman semula (Refresh)
        return back();
    }

    public function storeComment(Request $request)
    {
        $request->validate(['body' => 'required|string|max:500']);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => $request->type,
            'commentable_id' => $request->id,
            'body' => $request->body
        ]);

        // Cek apakah request datang dari JavaScript (AJAX)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->body,
                'user' => auth()->user()->name
            ]);
        }

        // Kalau JavaScript gagal/tidak jalan, kembalikan ke halaman semula (Refresh)
        return back();
    }
}