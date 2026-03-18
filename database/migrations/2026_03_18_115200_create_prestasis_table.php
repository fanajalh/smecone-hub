<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (opsional, set nullable kalau prestasi milik tim/umum)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('judul'); // Contoh: "LKS Web Technologies 2025"
            $table->text('deskripsi')->nullable();
            $table->string('nama_pemenang'); // Contoh: "Fana Jalaludin"
            $table->string('kategori_juara'); // Contoh: "Juara 1", "Medali Emas"
            $table->string('tingkat')->nullable(); // Contoh: "Kabupaten", "Provinsi", "Nasional"
            $table->date('tanggal')->nullable(); // Tanggal juara diraih
            $table->string('gambar')->nullable(); // Path untuk simpan foto sertifikat/piala
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
    }
};  