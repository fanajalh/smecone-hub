<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Mengizinkan kolom-kolom ini diisi data (mass assignment)
    protected $fillable = [
        'user_id',
        'marketplace_item_id',
        'invoice_id',
        'invoice_url',
        'amount',
        'status',
        'whatsapp_number',
    ];

    /**
     * Relasi ke tabel users (Pembeli)
     * Satu transaksi dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel marketplaces (Barang yang dibeli)
     * Satu transaksi terkait dengan satu barang di marketplace
     */
    public function marketplaceItem()
    {
        return $this->belongsTo(Marketplace::class, 'marketplace_item_id');
    }
}