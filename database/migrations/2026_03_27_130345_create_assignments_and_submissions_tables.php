<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan is_teacher pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_teacher')->default(false)->after('email');
        });

        // 2. Buat tabel assignments
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_thread_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('deadline');
            $table->timestamps();
        });

        // 3. Buat tabel submissions
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('repo_link');
            $table->boolean('is_private')->default(true);
            $table->integer('grade')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('assignments');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_teacher');
        });
    }
};