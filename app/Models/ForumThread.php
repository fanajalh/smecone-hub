<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_solved'
    ];

    // Relasi Pembuat Channel
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Chat / Balasan
    public function replies()
    {
        return $this->hasMany(ForumReply::class);
    }

    // Relasi Anggota Channel (Pivot)
    public function members()
    {
        return $this->belongsToMany(User::class, 'channel_user');
    }
}