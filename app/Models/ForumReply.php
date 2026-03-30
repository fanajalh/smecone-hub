<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumReply extends Model
{
    // 1. Tambahkan kolom baru ke fillable agar bisa diisi
    protected $fillable = [
        'forum_thread_id', 
        'user_id', 
        'content', 
        'is_best_answer',
        'reply_to_id',
        'is_edited',
        'poll_data',
        'reactions',
        'media_path',    // Path file media (gambar/video)
        'media_type'     // Tipe media: image / video
    ];

    // 2. Beri tahu Laravel bahwa kolom poll dan reactions itu tipe datanya Array (karena di database disimpen sebagai JSON)
    protected $casts = [
        'poll_data' => 'array',
        'reactions' => 'array',
        'is_edited' => 'boolean',
    ];

    // Relasi Asli
    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    public function thread() { 
        return $this->belongsTo(ForumThread::class, 'forum_thread_id'); 
    }

    // 3. RELASI BARU: Untuk menarik data pesan yang di-reply
    public function repliedMessage() { 
        return $this->belongsTo(ForumReply::class, 'reply_to_id'); 
    }
}