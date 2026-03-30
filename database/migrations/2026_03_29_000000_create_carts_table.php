<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('marketplace_id')->constrained('marketplaces')->onDelete('cascade');
            $table->unsignedInteger('qty')->default(1);
            $table->timestamps();

            // Satu user hanya bisa punya satu baris per item
            $table->unique(['user_id', 'marketplace_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
