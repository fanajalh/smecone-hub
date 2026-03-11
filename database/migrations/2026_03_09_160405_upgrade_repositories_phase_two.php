<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom jumlah download di tabel repositories
        Schema::table('repositories', function (Blueprint $table) {
            $table->unsignedBigInteger('downloads_count')->default(0)->after('demo_link');
        });

        // Tabel untuk Kolaborator (Tugas Kelompok)
        Schema::create('repository_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained('repositories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Tabel untuk Sistem Bintang/Like
        Schema::create('repository_stars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained('repositories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repository_stars');
        Schema::dropIfExists('repository_collaborators');
        Schema::table('repositories', function (Blueprint $table) {
            $table->dropColumn('downloads_count');
        });
    }
};