<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceReview extends Model
{
    protected $fillable = [
        'user_id',
        'marketplace_item_id',
        'transaction_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketplaceItem()
    {
        return $this->belongsTo(Marketplace::class, 'marketplace_item_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
