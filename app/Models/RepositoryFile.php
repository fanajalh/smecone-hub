<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepositoryFile extends Model
{
    protected $fillable = ['repository_id', 'file_name', 'file_path', 'file_size'];

    public function repository() {
        return $this->belongsTo(Repository::class);
    }
}