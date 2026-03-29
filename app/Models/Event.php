<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',          
        'deskripsi',
        'lokasi',         
        'kategori',       
        'tanggal_event',  
        'gambar'
    ];

    protected $casts = [
        'gambar' => 'array',
        'tanggal_event' => 'datetime'
    ];

    // 👇 INI YANG TADI KURANG 👇
    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}