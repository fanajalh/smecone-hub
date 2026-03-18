<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Contoh: "Smecone Photography Contest"
            $table->text('deskripsi')->nullable();
            $table->string('lokasi')->nullable(); // Contoh: "Lapangan Basket"
            $table->string('kategori')->nullable(); // Contoh: "LOMBA", "SEMINAR"
            $table->dateTime('tanggal_event')->nullable(); // Waktu acara
            $table->string('gambar')->nullable(); // Path poster event
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};