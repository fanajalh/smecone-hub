<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\LostAndFoundController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GitHttpController;

// Hapus kode ini kalau sudah selesai ngetes tampilannya ya!
Route::get('/404', function() { abort(404); });
Route::get('/403', function() { abort(403); });
Route::get('/419', function() { abort(419); });
Route::get('/500', function() { abort(500); });

// ==========================================
// 🚪 Pintu Gerbang Utama
// ==========================================
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// ==========================================
// 🔑 AUTHENTICATION
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// 🎓 ZONA SISWA (Hanya bisa diakses Siswa)
// ==========================================
Route::middleware(['auth', 'App\Http\Middleware\IsStudent'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/prestasi', function () {
        return view('prestasi.index');
    });

    Route::get('/event', function () {
        return view('event.index');
    });

    // --- REPOSITORY SYSTEM ---
    Route::get('/repository', [RepositoryController::class, 'index']);
    Route::get('/repository/create', [RepositoryController::class, 'create']);
    Route::post('/repository', [RepositoryController::class, 'store']);
    Route::get('/repository/{id}', [RepositoryController::class, 'show']);
    Route::post('/repository/{id}/upload', [RepositoryController::class, 'uploadFile']);
    Route::get('/repository/file/{id}/download', [RepositoryController::class, 'downloadFile']);
    Route::post('/repository/{id}/download-zip', [RepositoryController::class, 'downloadZip']);
    Route::delete('/repository/{id}/clear', [RepositoryController::class, 'clearFiles']);
    Route::get('/repository/file/{id}/preview', [RepositoryController::class, 'previewFile']);
    Route::post('/repository/{id}/star', [RepositoryController::class, 'toggleStar']);
    Route::post('/repository/{id}/collaborator', [RepositoryController::class, 'addCollaborator']);
    Route::delete('/repository/{id}/collaborator/{userId}', [RepositoryController::class, 'removeCollaborator']);
    Route::post('/repository/{id}/sync-git', [RepositoryController::class, 'syncGit']);
    Route::get('/repository/{id}/download-cli', [RepositoryController::class, 'downloadCli']);

    // --- MARKETPLACE ---
    Route::get('/marketplace', [MarketplaceController::class, 'index']);
    Route::get('/marketplace/create', [MarketplaceController::class, 'create']);
    Route::get('/marketplace/lapak-saya', [MarketplaceController::class, 'myLapak']);
    Route::post('/marketplace/register-store', [MarketplaceController::class, 'registerStore']);
    Route::post('/marketplace/store', [MarketplaceController::class, 'store']);
    Route::post('/marketplace/{id}/broadcast', [MarketplaceController::class, 'broadcastKeWa']);          
    Route::get('/marketplace/toko/{id}', [MarketplaceController::class, 'toko']);
    Route::get('/marketplace/{id}', [MarketplaceController::class, 'show']);
    Route::delete('/marketplace/{id}/delete', [MarketplaceController::class, 'destroy']);
    Route::post('/marketplace/{id}/toggle-sold', [MarketplaceController::class, 'toggleSold']);

    // --- FORUM & CHAT ---
    Route::get('/forum', [ForumController::class, 'index']);
    Route::post('/forum/{id}/join', [ForumController::class, 'joinChannel']);
    Route::get('/forum/{id}', [ForumController::class, 'show']);
    Route::post('/forum/{id}/message', [ForumController::class, 'storeMessage']);
    Route::get('/forum/{id}/messages', [ForumController::class, 'fetchMessages']);
    Route::put('/forum/message/{id}/edit', [ForumController::class, 'editMessage']);
    Route::delete('/forum/message/{id}/delete', [ForumController::class, 'deleteMessage']);
    Route::post('/forum/message/{id}/react', [ForumController::class, 'reactMessage']);

    // --- FORUM MANAJEMEN ---
    Route::get('/dashboard/channel', function () {
        return redirect('/dashboard');
    });
    Route::get('/dashboard/channel/create', [ForumController::class, 'createChannel']);
    Route::post('/dashboard/channel', [ForumController::class, 'storeChannel']); 
    Route::get('/dashboard/channel/{id}', [ForumController::class, 'show']);
    Route::get('/dashboard/channel/{id}/manage', [ForumController::class, 'manageChannel']);
    Route::get('/dashboard/channel/{id}/members', [ForumController::class, 'manageChannel']);
    Route::put('/dashboard/channel/{id}', [ForumController::class, 'updateChannel']);
    Route::post('/dashboard/channel/{id}/update', [ForumController::class, 'updateChannel']);
    Route::delete('/dashboard/channel/{id}/delete', [ForumController::class, 'deleteChannel']);
    Route::post('/dashboard/channel/{id}/members', [ForumController::class, 'addMember']);
    Route::delete('/dashboard/channel/{id}/members/{userId}', [ForumController::class, 'removeMember']);

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});

// ==========================================
// 🛡️ ZONA ADMIN (Hanya bisa diakses Admin)
// ==========================================
Route::middleware(['auth', 'App\Http\Middleware\IsAdmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
    // Tambahkan rute admin lainnya di sini nanti kalau butuh
});

// ==========================================
// 🌐 API & WEBHOOK (Tanpa Middleware Auth)
// ==========================================
Route::any('/git/{path}', [GitHttpController::class, 'handle'])->where('path', '.*');
Route::post('/git-hook/{id}/auto-sync', [RepositoryController::class, 'autoSyncGit']);
Route::post('/api/docs/push', [RepositoryController::class, 'pushFromCli'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);