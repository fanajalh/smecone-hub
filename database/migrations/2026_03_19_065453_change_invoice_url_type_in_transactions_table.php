<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ubah tipe kolom menjadi TEXT agar muat link panjang
            $table->text('invoice_url')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kembalikan ke string jika di-rollback
            $table->string('invoice_url', 255)->nullable()->change();
        });
    }
};