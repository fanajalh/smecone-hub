<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\RepositoryController; // 🔥 PENTING: Import controller repo lu

class GitHttpController extends Controller
{
    public function handle(Request $request, $path)
    {
        // 1. Lokasi program Git HTTP Backend di Windows kamu
        $backendPath = 'C:\Program Files\Git\mingw64\libexec\git-core\git-http-backend.exe';
        
        // 2. Folder tempat kita nyimpen repo-repo tadi
        $projectRoot = storage_path('app/git-repos');

        // 3. Merakit Environment Variables
        $env = array_merge($_SERVER, [
            'GIT_PROJECT_ROOT' => $projectRoot,
            'GIT_HTTP_EXPORT_ALL' => '1',
            'REQUEST_METHOD' => $request->method(),
            'QUERY_STRING' => $request->getQueryString() ?? '',
            'PATH_INFO' => '/' . $path,
            'CONTENT_TYPE' => $request->header('Content-Type') ?? '',
            'HTTP_ACCEPT' => $request->header('Accept') ?? '',
            'REMOTE_USER' => 'smecone_student',
        ]);

        // 4. Membuka jembatan langsung ke file git-http-backend.exe
        $options = [
            0 => ['pipe', 'r'], // STDIN (Masuk)
            1 => ['pipe', 'w'], // STDOUT (Keluar)
            2 => ['pipe', 'w']  // STDERR (Error)
        ];

        $process = proc_open('"' . $backendPath . '"', $options, $pipes, null, $env);

        if (!is_resource($process)) {
            abort(500, 'Gagal membuka jembatan Git Backend.');
        }

        // 5. Ngirim data dari Terminal lu ke program Git
        fwrite($pipes[0], $request->getContent());
        fclose($pipes[0]);

        // 6. Nangkep balasan dari program Git
        $output = stream_get_contents($pipes[1]);
        $errorOutput = stream_get_contents($pipes[2]); 
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        // ==============================================================
        // 🔥 SUPER HACK: AUTO-SYNC 100% PHP (BYPASS WINDOWS) 🔥
        // ==============================================================
        // Jika request method POST dan jalurnya adalah 'git-receive-pack',
        // itu artinya Terminal teman lu BARU SAJA BERHASIL NGE-PUSH!
        if ($request->isMethod('post') && strpos($path, 'git-receive-pack') !== false) {
            
            // Ambil nama folder dari URL, misal "1-muhammad-fachri-arfan.git"
            $folderName = basename(dirname($path)); 
            
            // Ambil angka ID-nya saja (angka 1)
            preg_match('/^(\d+)-/', $folderName, $matches);
            
            if (isset($matches[1])) {
                $repoId = $matches[1];
                
                // Diam-diam suruh Laravel mengekstrak file ke MySQL & Web
                try {
                    app(RepositoryController::class)->autoSyncGit($repoId);
                    Log::info("Auto-sync BERHASIL untuk Repo ID: $repoId");
                } catch (\Exception $e) {
                    Log::error("Auto-sync GAGAL untuk Repo ID $repoId: " . $e->getMessage());
                }
            }
        }
        // ==============================================================

        // 7. Memecah balasan menjadi Header dan Body
        if (strpos($output, "\r\n\r\n") !== false) {
            list($headersStr, $body) = explode("\r\n\r\n", $output, 2);
        } elseif (strpos($output, "\n\n") !== false) {
            list($headersStr, $body) = explode("\n\n", $output, 2);
        } else {
            return response($output, 200);
        }

        $headers = [];
        foreach (explode("\n", $headersStr) as $line) {
            $line = trim($line);
            if (!empty($line)) {
                list($key, $val) = explode(': ', $line, 2);
                $headers[$key] = $val;
            }
        }

        return response($body, 200, $headers);
    }
}