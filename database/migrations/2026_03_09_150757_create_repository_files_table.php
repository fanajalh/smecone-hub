<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repository_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained('repositories')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size'); // Untuk menyimpan ukuran file (KB/MB)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repository_files');
    }
};