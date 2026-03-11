<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('marketplaces');
        Schema::enableForeignKeyConstraints();

        Schema::create('marketplaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->text('description');
            $table->integer('price');
            $table->string('image')->nullable();
            $table->enum('category', ['Makanan', 'Alat Tulis', 'Elektronik', 'Jasa', 'Lainnya']);
            $table->enum('type', ['Ready Stock', 'Pre-Order'])->default('Ready Stock');
            $table->string('location')->nullable(); // Misal: Kantin, Kelas X PPLG 1, dll.
            $table->boolean('is_sold')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplaces');
    }
};