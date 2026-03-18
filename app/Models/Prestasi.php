<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional)
    protected $table = 'prestasis';

    // Kolom yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'user_id',        // Jika dihubungkan dengan akun siswa yang login
        'judul',          // Contoh: "LKS Web Technologies 2025"
        'deskripsi',      
        'nama_pemenang',  // Contoh: "Fana Jalaludin" atau "Tim Futsal Inti"
        'kategori_juara', // Contoh: "Juara 1"
        'tingkat',        // Contoh: "Provinsi", "Nasional"
        'tanggal',        // Tanggal diraihnya prestasi
        'gambar'          // Path foto/thumbnail
    ];

    // Relasi ke Model User (Siswa yang meraih prestasi)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
    }
    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}