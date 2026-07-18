<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-vercel', function () {
    $path = public_path();
    $files = [];
    
    // List everything in public directory
    if (is_dir($path)) {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            $files[] = $file->getPathname();
        }
    }
    
    return response()->json([
        'public_path' => $path,
        'public_exists' => is_dir($path),
        'files' => $files,
        'vercel_env' => $_ENV,
    ]);
});
