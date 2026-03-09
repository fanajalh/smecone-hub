<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostAndFound extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'image',
        'type',
        'status',
        'resolved_by'
    ];

    // Relasi: Barang ini diposting oleh siapa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Barang ini diselesaikan/dikembalikan oleh siapa
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}