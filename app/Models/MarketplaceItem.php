<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'type',
        'image',
        'whatsapp_number'
    ];

    // Relasi: Barang/jasa ini milik user siapa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}