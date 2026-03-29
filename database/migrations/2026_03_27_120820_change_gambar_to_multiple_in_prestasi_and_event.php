<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Convert existing strings to JSON format
        foreach (DB::table('prestasis')->whereNotNull('gambar')->get() as $row) {
            if (!str_starts_with($row->gambar, '[')) {
                DB::table('prestasis')->where('id', $row->id)->update([
                    'gambar' => json_encode([$row->gambar])
                ]);
            }
        }
        
        foreach (DB::table('events')->whereNotNull('gambar')->get() as $row) {
            if (!str_starts_with($row->gambar, '[')) {
                DB::table('events')->where('id', $row->id)->update([
                    'gambar' => json_encode([$row->gambar])
                ]);
            }
        }

        // 2. Change column to JSON
        Schema::table('prestasis', function (Blueprint $table) {
            $table->json('gambar')->nullable()->change();
        });
        
        Schema::table('events', function (Blueprint $table) {
            $table->json('gambar')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            $table->string('gambar')->nullable()->change();
        });
        
        Schema::table('events', function (Blueprint $table) {
            $table->string('gambar')->nullable()->change();
        });
    }
};