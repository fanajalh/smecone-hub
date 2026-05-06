<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Alter category enum to include Produk Digital
        DB::statement("ALTER TABLE marketplaces MODIFY category VARCHAR(255)");
    }

    public function down()
    {
        DB::statement("ALTER TABLE marketplaces MODIFY category ENUM('Makanan', 'Alat Tulis', 'Elektronik', 'Jasa', 'Lainnya')");
    }
};
