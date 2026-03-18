<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    // Ini wajib ada biar datanya boleh masuk ke database
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
}