<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'file_link', 'type'];
    public function user() { return $this->belongsTo(User::class); }
}