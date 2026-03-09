<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMessage extends Model
{
    protected $fillable = ['lost_and_found_id', 'user_id', 'message'];

    public function user() { 
        return $this->belongsTo(User::class); 
    }
    public function item() { 
        return $this->belongsTo(LostAndFound::class, 'lost_and_found_id'); 
    }
}