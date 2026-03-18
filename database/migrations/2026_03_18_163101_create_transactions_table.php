<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Pembeli
        $table->foreignId('marketplace_item_id')->constrained('marketplaces')->cascadeOnDelete(); // Barang yang dibeli
        $table->string('invoice_id')->nullable(); // ID Tagihan dari Xendit
        $table->string('invoice_url')->nullable(); // Link halaman bayar Xendit
        $table->decimal('amount', 12, 2); // Harga total
        $table->string('status')->default('PENDING'); // PENDING, PAID, FAILED
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
