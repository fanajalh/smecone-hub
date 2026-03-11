<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    protected $fillable = [
        'user_id', 'item_name', 'description', 'price', 
        'image', 'category', 'type', 'location', 'is_sold'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}