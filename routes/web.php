<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LostAndFoundController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\AdminController;

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsStudent;

Route::get('/', function () { 
    if (auth()->check()) {
        return auth()->user()->is_admin ? redirect('/admin') : redirect('/dashboard');
    }
    return redirect('/login'); 
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // -------------------------------------------------------------
    // ZONA BERSAMA (Bisa diakses Admin & Siswa)
    // -------------------------------------------------------------
    Route::get('/chat/item/{id}', [LostAndFoundController::class, 'chatRoom']);
    Route::post('/chat/item/{id}', [LostAndFoundController::class, 'sendMessage']);
    Route::get('/chat/item/{id}/fetch', [LostAndFoundController::class, 'fetchMessages']);

    // -------------------------------------------------------------
    // ZONA KHUSUS ADMIN KESISWAAN
    // -------------------------------------------------------------
    Route::middleware([IsAdmin::class])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/lost-found/{id}/edit', [LostAndFoundController::class, 'edit']); 
        Route::put('/admin/lost-found/{id}', [LostAndFoundController::class, 'update']); 
        Route::delete('/admin/lost-found/{id}', [LostAndFoundController::class, 'destroy']); 
        Route::post('/admin/lost-found/{id}/confirm', [LostAndFoundController::class, 'confirmAdmin']); 
        Route::post('/admin/lost-found/{id}/resolve', [LostAndFoundController::class, 'resolve']); 
    });

    // -------------------------------------------------------------
    // ZONA KHUSUS SISWA BIASA
    // -------------------------------------------------------------
    Route::middleware([IsStudent::class])->group(function () {
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/lost-found', [LostAndFoundController::class, 'index']);
        Route::get('/lost-found/create', [LostAndFoundController::class, 'create']);
        Route::post('/lost-found', [LostAndFoundController::class, 'store']);
        Route::get('/lost-found/{id}', [LostAndFoundController::class, 'show']);
        Route::get('/lost-found/{id}/edit', [LostAndFoundController::class, 'edit']); 
        Route::put('/lost-found/{id}', [LostAndFoundController::class, 'update']);
        Route::delete('/lost-found/{id}', [LostAndFoundController::class, 'destroy']);
        Route::post('/lost-found/{id}/resolve', [LostAndFoundController::class, 'resolve']); 
        
        Route::get('/marketplace', [MarketplaceController::class, 'index']);
        Route::get('/marketplace/create', [MarketplaceController::class, 'create']);
        Route::post('/marketplace/store', [MarketplaceController::class, 'store']);

        Route::get('/repository', [RepositoryController::class, 'index']);
        
        Route::get('/forum', [ForumController::class, 'index']);
        Route::post('/forum/{id}/join', [ForumController::class, 'joinChannel']);
        Route::get('/forum/{id}', [ForumController::class, 'show']);
        Route::post('/forum/{id}/chat', [ForumController::class, 'storeMessage']);
        Route::get('/forum/{id}/chats', [ForumController::class, 'fetchMessages']);
        
        Route::get('/dashboard/channel/create', [ForumController::class, 'createChannel']);
        Route::post('/dashboard/channel', [ForumController::class, 'storeChannel']);
        Route::get('/dashboard/channel/{id}/manage', [ForumController::class, 'manageChannel']);
        Route::put('/dashboard/channel/{id}', [ForumController::class, 'updateChannel']);
        Route::delete('/dashboard/channel/{id}', [ForumController::class, 'deleteChannel']);
        Route::post('/dashboard/channel/{id}/members', [ForumController::class, 'addMember']);
        Route::delete('/dashboard/channel/{id}/members/{userId}', [ForumController::class, 'removeMember']);
    });
});