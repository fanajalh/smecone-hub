<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Models\RepositoryFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use ZipArchive;

class RepositoryController extends Controller
{
    public function index(Request $request)
    {
        $major = $request->query('major');

        $repositories = Repository::withCount('stars')->with(['user', 'collaborators'])
            ->when($major, function($query, $major) {
                return $query->where('major', $major);
            })
            ->where(function($query) {
                $query->where('visibility', 'public')
                      ->orWhere('user_id', auth()->id())
                      ->orWhereHas('collaborators', function($q) {
                          $q->where('user_id', auth()->id());
                      });
            })
            ->latest()
            ->get();

        return view('repository.index', compact('repositories', 'major'));
    }

    public function create() { 
        return view('repository.create'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
            'major' => 'required|string',
            'demo_link' => 'nullable|url',
        ]);

        $repoName = strtolower(str_replace(' ', '-', $request->name));
        $user = auth()->user();

        $gitFolderName = $user->id . '-' . $repoName . '.git';
        $gitPath = storage_path('app/git-repos/' . $gitFolderName);

        File::ensureDirectoryExists($gitPath, 0755, true);

        $result = Process::run('"C:\\Program Files\\Git\\cmd\\git.exe" init --bare "' . $gitPath . '"');

        if (!$result->successful()) {
            return back()->with('error', 'Gagal eksekusi terminal Git: ' . $result->errorOutput());
        }

        $repository = Repository::create([
            'user_id' => $user->id,
            'name' => $repoName,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'major' => $request->major,
            'demo_link' => $request->demo_link,
            'git_path' => $gitPath,
        ]);

        $hookPath = $gitPath . '/hooks/post-receive';
        $webhookUrl = url('/git-hook/' . $repository->id . '/auto-sync');
        
        $hookContent = "#!/bin/sh\n\n";
        $hookContent .= "echo \"\"\n";
        $hookContent .= "echo \"================================================\"\n";
        $hookContent .= "echo \"🚀 [SMECONE HUB] Memproses Auto-Sync ke Website...\"\n";
        $hookContent .= "echo \"================================================\"\n";
        $hookContent .= "curl -s -X POST \"{$webhookUrl}\"\n";
        $hookContent .= "echo \"✅ [SMECONE HUB] Auto-Sync Selesai! File berhasil diekstrak.\"\n";
        $hookContent .= "echo \"\"\n";
        
        File::put($hookPath, $hookContent);

        return redirect('/repository')->with('success', 'Repositori berhasil dibentuk di Server Smecone Hub!');
    }

    public function show($id)
    {
        $repository = Repository::with(['user', 'files', 'collaborators', 'stars'])->findOrFail($id);
        
        $isOwner = $repository->user_id == auth()->id();
        $isCollaborator = $repository->collaborators->contains(auth()->id());

        if ($repository->visibility == 'private' && !$isOwner && !$isCollaborator && !auth()->user()->is_admin) {
            abort(403, 'Akses ditolak.');
        }

        $allUsers = User::where('id', '!=', $repository->user_id)
                        ->whereNotIn('id', $repository->collaborators->pluck('id'))->get();

        $gitLog = [];
        if ($repository->git_path && File::exists($repository->git_path)) {
            $logProcess = Process::run('"C:\\Program Files\\Git\\cmd\\git.exe" --git-dir="' . $repository->git_path . '" log --pretty=format:"%h|%an|%s|%cr" -n 5');
            
            if ($logProcess->successful() && !empty(trim($logProcess->output()))) {
                $lines = explode("\n", trim($logProcess->output()));
                foreach($lines as $line) {
                    $parts = explode('|', $line);
                    if(count($parts) >= 4) {
                        $gitLog[] = [
                            'hash' => $parts[0],
                            'author' => $parts[1],
                            'message' => $parts[2],
                            'time' => $parts[3]
                        ];
                    }
                }
            }
        }

        $readmeFile = $repository->files->firstWhere(function ($file) { return strtolower(basename($file->file_name)) === 'readme.md'; });
        $readmeContent = null;
        if ($readmeFile && Storage::disk('public')->exists($readmeFile->file_path)) {
            $readmeContent = Str::markdown(Storage::disk('public')->get($readmeFile->file_path));
        }

        return view('repository.show', compact('repository', 'readmeContent', 'isOwner', 'isCollaborator', 'allUsers', 'gitLog'));
    }

    public function uploadFile(Request $request, $id)
    {
        $repository = Repository::findOrFail($id);
        if ($repository->user_id !== auth()->id() && !$repository->collaborators->contains(auth()->id())) abort(403);

        $request->validate(['files.*' => 'required|file|max:51200']);

        if($request->hasFile('files')) {
            $totalExtracted = 0;
            foreach($request->file('files') as $file) {
                $ext = strtolower($file->getClientOriginalExtension());
                $originalName = $file->getClientOriginalName();

                if ($ext === 'zip') {
                    $zip = new ZipArchive();
                    if ($zip->open($file->getRealPath()) === TRUE) {
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                            $filename = $zip->getNameIndex($i);
                            if (substr($filename, -1) == '/') continue;
                            
                            $content = $zip->getFromIndex($i);
                            $bytes = strlen($content);
                            $fileSize = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB';
                            $path = 'repositories/' . $repository->id . '/' . uniqid() . '_' . basename($filename);
                            
                            Storage::disk('public')->put($path, $content);
                            RepositoryFile::create(['repository_id' => $repository->id, 'file_name' => $filename, 'file_path' => $path, 'file_size' => $fileSize]);
                            $totalExtracted++;
                        }
                        $zip->close();
                    }
                } else {
                    $bytes = $file->getSize();
                    $fileSize = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB';
                    $path = $file->storeAs('repositories/' . $repository->id, uniqid().'_'.$originalName, 'public');
                    RepositoryFile::create(['repository_id' => $repository->id, 'file_name' => $originalName, 'file_path' => $path, 'file_size' => $fileSize]);
                    $totalExtracted++;
                }
            }
            $repository->touch(); 
            return back()->with('success', "Sukses mengunggah/mengekstrak $totalExtracted file!");
        }
        return back()->with('error', 'Tidak ada file yang diunggah.');
    }

    public function downloadFile($fileId)
    {
        $file = RepositoryFile::with('repository')->findOrFail($fileId);
        $repo = $file->repository;
        if ($repo->visibility == 'private' && $repo->user_id !== auth()->id() && !$repo->collaborators->contains(auth()->id())) abort(403);
        $repo->increment('downloads_count'); 
        return response()->download(storage_path('app/public/' . $file->file_path), basename($file->file_name));
    }

    public function downloadZip(Request $request, $id)
    {
        $repository = Repository::with('files')->findOrFail($id);
        if ($repository->visibility == 'private' && $repository->user_id !== auth()->id() && !$repository->collaborators->contains(auth()->id())) abort(403);
        $fileIds = $request->input('file_ids', []); 
        $files = empty($fileIds) ? $repository->files : RepositoryFile::whereIn('id', $fileIds)->where('repository_id', $id)->get();
        if ($files->isEmpty()) return back()->with('error', 'Tidak ada file untuk didownload.');

        $zip = new ZipArchive();
        $zipFileName = $repository->name . '_' . time() . '.zip';
        $zipPath = storage_path('app/public/temp/' . $zipFileName);
        if (!File::exists(storage_path('app/public/temp'))) File::makeDirectory(storage_path('app/public/temp'), 0755, true);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) $zip->addFile($filePath, $file->file_name);
            }
            $zip->close();
        }
        $repository->increment('downloads_count'); 
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function clearFiles($id)
    {
        $repository = Repository::findOrFail($id);
        if ($repository->user_id !== auth()->id() && !$repository->collaborators->contains(auth()->id())) abort(403);
        foreach($repository->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }
        return back()->with('success', 'Repositori berhasil dikosongkan.');
    }

    public function previewFile($fileId)
    {
        $file = RepositoryFile::with('repository')->findOrFail($fileId);
        $repo = $file->repository;
        if ($repo->visibility == 'private' && $repo->user_id !== auth()->id() && !$repo->collaborators->contains(auth()->id())) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }
        $path = storage_path('app/public/' . $file->file_path);
        if (!file_exists($path)) return response()->json(['error' => 'File tidak ditemukan.'], 404);

        $ext = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
        $images = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];
        $codes = ['php', 'js', 'html', 'css', 'json', 'py', 'txt', 'md', 'java', 'c', 'cpp', 'sql', 'xml', 'env'];

        if (in_array($ext, $images)) {
            return response()->json(['type' => 'image', 'url' => asset('storage/' . $file->file_path)]);
        } elseif (in_array($ext, $codes)) {
            $content = file_get_contents($path);
            if (strlen($content) > 2097152) return response()->json(['type' => 'too_large']);
            return response()->json(['type' => 'code', 'content' => mb_convert_encoding($content, 'UTF-8', 'UTF-8'), 'ext' => $ext]);
        } else {
            return response()->json(['type' => 'unsupported']);
        }
    }

    public function toggleStar($id) {
        $repository = Repository::findOrFail($id);
        $repository->stars()->toggle(auth()->id());
        return back();
    }

    public function addCollaborator(Request $request, $id) {
        $repository = Repository::findOrFail($id);
        if ($repository->user_id !== auth()->id()) abort(403); 
        $request->validate(['user_id' => 'required|exists:users,id']);
        $repository->collaborators()->attach($request->user_id);
        return back()->with('success', 'Anggota tim berhasil ditambahkan!');
    }

    public function removeCollaborator($id, $userId) {
        $repository = Repository::findOrFail($id);
        if ($repository->user_id !== auth()->id()) abort(403);
        $repository->collaborators()->detach($userId);
        return back()->with('success', 'Anggota tim berhasil dihapus.');
    }

    public function syncGit($id)
    {
        $repository = Repository::findOrFail($id);
        
        if ($repository->user_id !== auth()->id() && !$repository->collaborators->contains(auth()->id())) {
            abort(403, 'Akses ditolak.');
        }

        $count = $this->processGitExtraction($repository);
        if ($count === false) {
            return back()->with('error', 'Gagal membaca Git atau repository masih kosong.');
        }

        $repository->touch();
        return back()->with('success', "Sinkronisasi Berhasil! $count file ditarik dari Server Git ke Web.");
    }

    public function autoSyncGit($id)
    {
        $repository = Repository::findOrFail($id);
        $count = $this->processGitExtraction($repository);
        if ($count === false) {
            return response()->json(['status' => 'error', 'message' => 'Failed to extract or empty repo.']);
        }
        $repository->touch();
        return response()->json(['status' => 'success', 'message' => "Auto-sync completed. Extracted $count files."]);
    }

    private function processGitExtraction($repository)
    {
        if (!$repository->git_path || !File::exists($repository->git_path)) {
            return false;
        }

        $process = Process::run('"C:\\Program Files\\Git\\cmd\\git.exe" --git-dir="' . $repository->git_path . '" ls-tree -r HEAD --name-only');

        if (!$process->successful() || empty(trim($process->output()))) {
            return false;
        }

        $files = explode("\n", trim($process->output()));

        foreach($repository->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete();
        }

        $count = 0;
        foreach ($files as $filename) {
            $filename = trim($filename);
            if (empty($filename)) continue;

            $fileContentProcess = Process::run('"C:\\Program Files\\Git\\cmd\\git.exe" --git-dir="' . $repository->git_path . '" show HEAD:"' . $filename . '"');

            if ($fileContentProcess->successful()) {
                $content = $fileContentProcess->output();
                $bytes = strlen($content);
                $fileSize = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB';
                
                $path = 'repositories/' . $repository->id . '/' . uniqid() . '_' . basename($filename);
                Storage::disk('public')->put($path, $content);

                RepositoryFile::create([
                    'repository_id' => $repository->id,
                    'file_name' => $filename,
                    'file_path' => $path,
                    'file_size' => $fileSize
                ]);
                $count++;
            }
        }

        return $count;
    }

    // 🔥 FUNGSI BARU: GENERATOR CLI DOCU-PUSH OTOMATIS (WARNA-WARNI ALA LINUX/VSCODE) 🔥
    public function downloadCli($id)
    {
        $repository = Repository::findOrFail($id);
        $user = auth()->user();

        if ($repository->user_id !== $user->id && !$repository->collaborators->contains($user->id)) {
            abort(403, 'Waduh, mau ngintip ya? Akses ditolak!');
        }

        // ==========================================
        // Definisi Kode Warna ANSI (Mirip Tema VS Code)
        // ==========================================
        $reset   = "\e[0m";   // Reset ke warna terminal bawaan
        $bold    = "\e[1m";   // Teks tebal
        $gray    = "\e[90m";  // Abu-abu gelap (untuk border/komentar)
        $magenta = "\e[35m";  // Ungu/Magenta (untuk Judul)
        $cyan    = "\e[36m";  // Biru Muda (untuk label/info)
        $green   = "\e[32m";  // Hijau (untuk value/sukses)
        $yellow  = "\e[33m";  // Kuning (untuk warning)
        $red     = "\e[31m";  // Merah (untuk error)
        $blue    = "\e[34m";  // Biru

        $content = "@echo off\n";
        // chcp 65001 digunakan agar terminal CMD mendukung UTF-8 (bisa nampilin Emoji)
        $content .= "chcp 65001 >nul\n"; 
        $content .= "title SMECONE HUB - Push CLI [%~1]\n\n";
        
        // Header Tampilan CLI
        $content .= "echo {$gray}========================================{$reset}\n";
        $content .= "echo {$magenta}{$bold} 🚀 SMECONE HUB DOCU-PUSH CLI{$reset}\n";
        $content .= "echo {$gray}========================================{$reset}\n\n";
        
        // Pengecekan argumen jika kosong
        $content .= "if \"%~1\"==\"\" (\n";
        $content .= "    echo {$red}[!] ERROR: Nama file belum dimasukkan, bos!{$reset}\n";
        $content .= "    echo {$yellow}[i] Cara pakai: docpush namadokumen.pdf{$reset}\n";
        $content .= "    echo {$gray}========================================{$reset}\n";
        $content .= "    pause\n";
        $content .= "    exit /b\n";
        $content .= ")\n\n";
        
        // --- DATA OTOMATIS DARI LARAVEL ---
        $content .= ":: Konfigurasi Otomatis\n";
        $content .= "set EMAIL=" . $user->email . "\n";
        $content .= "set REPO_ID=" . $repository->id . "\n";
        $content .= "set SERVER_URL=" . url('/api/docs/push') . "\n\n";
        
        // Tampilan Proses Eksekusi (Syntax Highlighting Ala VS Code)
        $content .= "echo {$cyan}[*] Menyiapkan pengiriman...{$reset}\n";
        $content .= "echo {$cyan}[*] File       : {$green}%~1{$reset}\n";
        $content .= "echo {$cyan}[*] Repositori : {$yellow}" . strtoupper($repository->name) . "{$reset}\n";
        $content .= "echo {$cyan}[*] User       : {$blue}" . $user->name . "{$reset}\n";
        $content .= "echo {$cyan}[*] Menghubungkan ke Server Smecone Hub...{$reset}\n";
        $content .= "echo.\n\n";
        
        // Eksekusi CURL
        $content .= "echo {$gray}--- Server Response ---{$reset}\n";
        $content .= "curl -s -X POST ^\n";
        $content .= "     -F \"email=%EMAIL%\" ^\n";
        $content .= "     -F \"repo_id=%REPO_ID%\" ^\n";
        $content .= "     -F \"file=@%~1\" ^\n";
        $content .= "     %SERVER_URL%\n\n";
        
        // Footer CLI
        $content .= "echo.\n";
        $content .= "echo {$gray}-----------------------{$reset}\n";
        $content .= "echo {$green}{$bold}[v] Proses Selesai! Silahkan cek di dashboard web.{$reset}\n";
        $content .= "echo {$gray}========================================{$reset}\n";
        $content .= "pause\n";

        // Generate File .bat
        $fileName = 'docpush-' . strtolower(str_replace(' ', '-', $repository->name)) . '.bat';

        return response($content)
            ->withHeaders([
                'Content-Type' => 'application/x-bat',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
    }

    // 🔥 FUNGSI BARU: Endpoint untuk menerima file dari CLI 🔥
    public function pushFromCli(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email',
            'repo_id' => 'required|exists:repositories,id',
            'file' => 'required|file|max:51200' // Maksimal 50MB
        ]);

        // 2. Ambil User dan Repositori
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User dengan email tersebut tidak ditemukan.'], 404);
        }

        $repository = Repository::findOrFail($request->repo_id);

        // 3. Pastikan user tersebut berhak upload ke repo ini (Owner / Collaborator)
        if ($repository->user_id !== $user->id && !$repository->collaborators->contains($user->id)) {
            return response()->json(['error' => 'Akses ditolak! Kamu bukan pemilik atau kolaborator repositori ini.'], 403);
        }

        // 4. Proses Simpan File
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $path = $file->storeAs('repositories/' . $repository->id, uniqid() . '_' . $originalName, 'public');

        // 5. Simpan ke Database
        $bytes = $file->getSize();
        $fileSize = ($bytes >= 1048576) ? number_format($bytes / 1048576, 2) . ' MB' : number_format($bytes / 1024, 2) . ' KB';
        
        RepositoryFile::create([
            'repository_id' => $repository->id,
            'file_name' => $originalName,
            'file_path' => $path,
            'file_size' => $fileSize
        ]);

        $repository->touch(); // Update last_modified pada tabel repository

        // 6. Return response sukses ke terminal cmd
        return response()->json([
            'status' => 'success',
            'message' => 'Mantap! File ' . $originalName . ' berhasil dikirim ke SMECONE HUB.'
        ], 200);
    }
}