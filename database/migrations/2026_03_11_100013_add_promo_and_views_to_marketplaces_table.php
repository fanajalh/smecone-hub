<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->unsignedBigInteger('views_count')->default(0)->after('is_sold');
            $table->boolean('is_promoted')->default(false)->after('views_count');
        });
    }

    public function down(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'is_promoted']);
        });
    }
};