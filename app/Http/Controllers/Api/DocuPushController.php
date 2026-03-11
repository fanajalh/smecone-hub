<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repository;
use App\Models\RepositoryFile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DocuPushController extends Controller
{
    public function push(Request $request)
    {
        // 1. Validasi Input dari Terminal
        $request->validate([
            'email' => 'required|email', // Tanda pengenal sementara
            'repo_id' => 'required|exists:repositories,id',
            'file' => 'required|file|max:20480' // Maksimal ukuran 20MB
        ]);

        // 2. Cari siapa yang nge-push berdasarkan email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User dengan email tersebut tidak ditemukan!'], 404);
        }

        // 3. Cari Repository yang dituju
        $repository = Repository::find($request->repo_id);
        
        // Cek apakah user ini punya hak akses ke repo tersebut
        $isOwner = $repository->user_id == $user->id;
        $isCollaborator = $repository->collaborators->contains($user->id);

        if (!$isOwner && !$isCollaborator) {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak! Kamu bukan anggota dari repositori ini.'], 403);
        }

        // 4. Proses Upload File dari Terminal ke Server
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            
            // Simpan file fisik ke storage Laravel
            $path = $file->storeAs('repositories/' . $repository->id, uniqid().'_'.$originalName, 'public');

            // Hitung ukuran file untuk UI Web
            $bytes = $file->getSize();
            $fileSize = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB';

            // Simpan datanya ke MySQL agar muncul di halaman web
            RepositoryFile::create([
                'repository_id' => $repository->id,
                'file_name' => $originalName,
                'file_path' => $path,
                'file_size' => $fileSize,
            ]);

            // Update waktu "last updated" di repo
            $repository->touch();

            return response()->json([
                'status' => 'success', 
                'message' => "🔥 BAM! Dokumen '{$originalName}' sukses di-push ke Smecone Hub!"
            ], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'File gagal diterima oleh server.'], 400);
    }
}