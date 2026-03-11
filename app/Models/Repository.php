<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    // Tambahkan git_path ke dalam array
    protected $fillable = [
        'user_id', 'name', 'description', 'visibility', 
        'major', 'demo_link', 'downloads_count', 'git_path'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function files() {
        return $this->hasMany(RepositoryFile::class);
    }

    public function collaborators() {
        return $this->belongsToMany(User::class, 'repository_collaborators');
    }

    public function stars() {
        return $this->belongsToMany(User::class, 'repository_stars');
    }
}