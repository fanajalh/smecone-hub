<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom ENUM secara aman menggunakan DB statement
        // Tambahkan opsi 'pending' sebagai default
        DB::statement("ALTER TABLE lost_and_founds MODIFY COLUMN status ENUM('pending', 'active', 'resolved') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Mengembalikan ke kondisi semula jika di-rollback
        DB::statement("ALTER TABLE lost_and_founds MODIFY COLUMN status ENUM('active', 'resolved') DEFAULT 'active'");
    }
};