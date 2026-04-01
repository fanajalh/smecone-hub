<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_thread_id',
        'user_id',
        'status',
    ];

    public function forumThread()
    {
        return $this->belongsTo(ForumThread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
