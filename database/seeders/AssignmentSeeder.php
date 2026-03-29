<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ForumThread;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT ROLE USER (Admin, Teacher, Student)
        $admin = User::updateOrCreate(
            ['email' => 'admin@smecone.com'],
            [
                'name' => 'Admin Smecone',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'is_teacher' => false,
                'nis' => '000000',
            ]
        );

        $teacher = User::updateOrCreate(
            ['email' => 'pak_eko@smecone.com'],
            [
                'name' => 'Pak Eko (Guru RPL)',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_teacher' => true,
                'nis' => '111111',
            ]
        );

        $student1 = User::updateOrCreate(
            ['email' => 'budi@student.com'],
            [
                'name' => 'Budi Sudarsono',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_teacher' => false,
                'nis' => '222001',
            ]
        );

        $student2 = User::updateOrCreate(
            ['email' => 'ani@student.com'],
            [
                'name' => 'Ani Wijaya',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_teacher' => false,
                'nis' => '222002',
            ]
        );

        // 2. BUAT CHANNEL FORUM (XI RPL 1)
        $channel = ForumThread::updateOrCreate(
            ['title' => 'xi-rpl-1'],
            [
                'user_id' => $teacher->id,
                'content' => 'Channel khusus diskusi dan penugasan kelas XI RPL 1.',
                'is_solved' => false,
            ]
        );

        // Tambahkan semua user ke channel ini (Join)
        $channel->members()->syncWithoutDetaching([$admin->id, $teacher->id, $student1->id, $student2->id]);

        // 3. BUAT TUGAS (ASSIGNMENT)
        $assignment = Assignment::updateOrCreate(
            ['title' => 'Tugas CRUD Laravel & Database'],
            [
                'forum_thread_id' => $channel->id,
                'description' => 'Buatlah aplikasi CRUD sederhana menggunakan Laravel 11. Kumpulkan link GitHub repository kalian di sini.',
                'deadline' => now()->addDays(7),
            ]
        );

        // 4. BUAT PENGUMPULAN (SUBMISSION) - Contoh Budi sudah mengumpulkan
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => $student1->id,
            ],
            [
                'repo_link' => 'https://github.com/budi/laravel-crud-tugas',
                'is_private' => true,
                'grade' => 90, // Sudah dinilai pak eko
            ]
        );

        // Ani belum dinilai
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => $student2->id,
            ],
            [
                'repo_link' => 'https://github.com/ani-wijaya/my-project',
                'is_private' => true,
                'grade' => null,
            ]
        );
    }
}