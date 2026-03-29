<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\ForumThread;
use App\Models\Submission;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    // 1. store: Untuk Guru membuat Tugas Baru di dalam sebuah Channel.
    public function store(Request $request, ForumThread $forumThread)
    {
        // Pastikan hanya guru yang bisa membuat tugas
        if (!auth()->user()->is_teacher) {
            return back()->with('error', 'Hanya Guru yang bisa membuat tugas.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
        ]);

        $forumThread->assignments()->create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
        ]);

        return back()->with('success', 'Tugas berhasil dibuat!');
    }

    // 2. submit: Untuk Murid mengumpulkan link tugas
    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'repo_link' => 'required|url',
        ]);

        // Murid hanya bisa submit jika belum ada submission
        Submission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'user_id' => auth()->id(),
            ],
            [
                'repo_link' => $request->repo_link,
                'is_private' => true, // Default private for anti-plagiarism
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    // 3. grade: Untuk Guru memberikan nilai
    public function grade(Request $request, Submission $submission)
    {
        if (!auth()->user()->is_teacher) {
            return back()->with('error', 'Hanya Guru yang bisa memberi nilai.');
        }

        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
        ]);

        $submission->update([
            'grade' => $request->grade,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    // 4. togglePrivacy: Untuk Murid membuka gembok tugasnya
    public function togglePrivacy(Submission $submission)
    {
        // Pastikan hanya pemilik submission yang bisa toggle
        if (auth()->id() !== $submission->user_id) {
            abort(403);
        }

        $submission->update([
            'is_private' => !$submission->is_private,
        ]);

        return back()->with('success', 'Privasi tugas diperbarui!');
    }
}