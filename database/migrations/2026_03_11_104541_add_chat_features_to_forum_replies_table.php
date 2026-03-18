<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sesuaikan nama tabelnya dengan tabel yang kamu pakai untuk nyimpen chat (asumsi saya: forum_replies atau item_messages)
        // Di sini saya pakai 'forum_replies' sesuai standar forum
        Schema::table('forum_replies', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()->constrained('forum_replies')->nullOnDelete();
            $table->boolean('is_edited')->default(false);
            $table->json('poll_data')->nullable(); // Untuk nyimpen pertanyaan & opsi polling
            $table->json('reactions')->nullable(); // Untuk nyimpen data siapa react apa
        });
    }

    public function down(): void
    {
        Schema::table('forum_replies', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn(['reply_to_id', 'is_edited', 'poll_data', 'reactions']);
        });
    }
};