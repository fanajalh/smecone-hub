<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add fields to forum_threads
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->boolean('is_private')->default(false)->after('is_solved');
            $table->string('invite_code')->nullable()->unique()->after('is_private');
        });

        // Create channel_requests table
        Schema::create('channel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_thread_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            // A user can only request once per channel
            $table->unique(['forum_thread_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_requests');
        
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn(['is_private', 'invite_code']);
        });
    }
};
