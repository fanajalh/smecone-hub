<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    protected $fillable = [
        'user_id', 'item_name', 'description', 'price', 
        'image', 'category', 'type', 'location', 'is_sold',
        'views_count', 'is_promoted', 'stock',
        'format', 'digital_link', 'variants_config'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reviews() {
        return $this->hasMany(MarketplaceReview::class, 'marketplace_item_id');
    }
}