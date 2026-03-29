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
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\PaymentController;

Route::get('/404', function() { abort(404); });
Route::get('/403', function() { abort(403); });
Route::get('/419', function() { abort(419); });
Route::get('/500', function() { abort(500); });

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// AUTH
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// GOOGLE AUTH
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

// ZONA SISWA
Route::middleware(['auth', 'App\Http\Middleware\IsStudent'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/prestasi', function () {
        $prestasis = \App\Models\Prestasi::with(['likes', 'comments.user'])->latest()->get();
        return view('prestasi.index', compact('prestasis'));
    });

    Route::get('/event', function () {
        $events = \App\Models\Event::with(['likes', 'comments.user'])->latest()->get();
        return view('event.index', compact('events'));
    });

    Route::post('/interaction/like', [InteractionController::class, 'toggleLike'])->name('like.toggle');
    Route::post('/interaction/comment', [InteractionController::class, 'storeComment'])->name('comment.store');

    // REPOSITORY
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

    // MARKETPLACE
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/create', [MarketplaceController::class, 'create']);
    Route::get('/marketplace/lapak-saya', [MarketplaceController::class, 'myLapak'])->name('marketplace.lapak');
    Route::get('/marketplace/penjualan', [PaymentController::class, 'salesHistory'])->name('marketplace.sales');
    Route::get('/marketplace/purchases', [App\Http\Controllers\PaymentController::class, 'purchaseHistory'])->name('marketplace.purchases');
    Route::post('/marketplace/register-store', [MarketplaceController::class, 'registerStore']);
    Route::post('/marketplace/store', [MarketplaceController::class, 'store']);
    Route::post('/marketplace/{id}/broadcast', [MarketplaceController::class, 'broadcastKeWa']);          
    Route::get('/marketplace/toko/{id}', [MarketplaceController::class, 'toko']);
    Route::get('/marketplace/{id}', [MarketplaceController::class, 'show'])->name('marketplace.show');
    Route::delete('/marketplace/{id}/delete', [MarketplaceController::class, 'destroy']);
    Route::post('/marketplace/{id}/toggle-sold', [MarketplaceController::class, 'toggleSold']);
    Route::get('/marketplace/payment/{id}', [PaymentController::class, 'paymentStatus'])->name('marketplace.payment.status');
    
    // Rute untuk Update Profil Toko (Banner & PP)
    Route::post('/marketplace/update-store-profile', [App\Http\Controllers\MarketplaceController::class, 'updateStoreProfile'])->name('marketplace.updateStoreProfile');

    // Rute untuk Update WA Penjual di Lapak
    Route::post('/marketplace/update-wa', [App\Http\Controllers\MarketplaceController::class, 'updateStoreWa'])->name('marketplace.updateWa');

    // PAYMENT SYSTEM
    Route::get('/marketplace/{id}/checkout', [PaymentController::class, 'checkoutConfirm'])->name('marketplace.checkout.confirm');
    Route::post('/marketplace/{id}/checkout/direct', [PaymentController::class, 'processDirectPayment'])->name('marketplace.checkout.direct');

    // FORUM
    Route::get('/forum', [ForumController::class, 'index']);
    Route::post('/forum/{id}/join', [ForumController::class, 'joinChannel']);
    Route::get('/forum/{id}', [ForumController::class, 'show']);
    Route::post('/forum/{id}/message', [ForumController::class, 'storeMessage']);
    Route::get('/forum/{id}/messages', [ForumController::class, 'fetchMessages']);
    Route::put('/forum/message/{id}/edit', [ForumController::class, 'editMessage']);
    Route::delete('/forum/message/{id}/delete', [ForumController::class, 'deleteMessage']);
    Route::post('/forum/message/{id}/react', [ForumController::class, 'reactMessage']);

    // ASSIGNMENTS
    Route::post('/forum/{forumThread}/assignment', [\App\Http\Controllers\AssignmentController::class, 'store'])->name('assignment.store');
    Route::post('/assignment/{assignment}/submit', [\App\Http\Controllers\AssignmentController::class, 'submit'])->name('assignment.submit');
    Route::post('/submission/{submission}/grade', [\App\Http\Controllers\AssignmentController::class, 'grade'])->name('submission.grade');
    Route::post('/submission/{submission}/toggle-privacy', [\App\Http\Controllers\AssignmentController::class, 'togglePrivacy'])->name('submission.toggle-privacy');

    Route::get('/dashboard/channel', function () { return redirect('/dashboard'); });
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

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
});

// ZONA ADMIN
Route::middleware(['auth', 'App\Http\Middleware\IsAdmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
    Route::get('/admin/prestasi/create', [AdminController::class, 'createPrestasi']);
    Route::post('/admin/prestasi', [AdminController::class, 'storePrestasi']);
    Route::delete('/admin/prestasi/{id}/delete', [AdminController::class, 'destroyPrestasi']);
    Route::get('/admin/event/create', [AdminController::class, 'createEvent']);
    Route::post('/admin/event', [AdminController::class, 'storeEvent']);
    Route::delete('/admin/event/{id}/delete', [AdminController::class, 'destroyEvent']);
});

// API & WEBHOOK
Route::any('/git/{path}', [GitHttpController::class, 'handle'])->where('path', '.*');
Route::post('/git-hook/{id}/auto-sync', [RepositoryController::class, 'autoSyncGit']);
Route::post('/api/docs/push', [RepositoryController::class, 'pushFromCli'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// WEBHOOK XENDIT
Route::post('/api/xendit/callback', [PaymentController::class, 'handleWebhook'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/tes-bayar/{id}', function($id) {
    // Tambahin ->with() biar data nama user & judul barang kebawa buat isi pesan WA
    $transaction = \App\Models\Transaction::with(['user', 'marketplaceItem', 'marketplaceItem.user'])->find($id);
    
    if(!$transaction) return "Transaksi nggak ketemu bos!";

    // 1. LANGSUNG HACK DATABASE JADI LUNAS
    $transaction->update(['status' => 'PAID']);
    if ($transaction->marketplaceItem) {
        $transaction->marketplaceItem->update(['is_sold' => true]);
    }

    // 2. TRIGGER WHATSAPP BOT LANGSUNG DARI SINI! 🚀
    $botUrl = 'http://localhost:3000/send-message'; 
    
    // Notif ke Pembeli
    if ($transaction->whatsapp_number) {
        try {
            \Illuminate\Support\Facades\Http::timeout(5)->post($botUrl, [
                'number' => $transaction->whatsapp_number,
                'message' => "Halo kak *{$transaction->user->name}*! 🛒\n\nPembayaran kakak untuk pesanan *{$transaction->marketplaceItem->title}* sebesar *Rp ".number_format($transaction->amount, 0, ',', '.')."* telah kami terima dan *BERHASIL*.\n\nPenjual akan segera memproses pesanan kakak. Mohon kesediaannya untuk menunggu ya. Terima kasih! 🚀\n\n_SMEconE Hub_"
            ]);
        } catch (\Exception $e) {
            // Abaikan error
        }
    }

    // Notif ke Penjual
    $sellerWa = $transaction->marketplaceItem->user->whatsapp_number ?? null;
    if ($sellerWa) {
        try {
            $waPembeli = str_starts_with($transaction->whatsapp_number, '62') ? '+' . $transaction->whatsapp_number : $transaction->whatsapp_number;
            $linkWaPembeli = 'https://wa.me/' . ltrim($transaction->whatsapp_number, '+');

            \Illuminate\Support\Facades\Http::timeout(5)->post($botUrl, [
                'number' => $sellerWa,
                'message' => "🔔 *PESANAN BARU MASUK!* 🔔\n\nSelamat! Barang jualanmu *{$transaction->marketplaceItem->title}* telah LUNAS dibayar oleh *{$transaction->user->name}*.\n\n*Detail Pesanan:*\nNominal: Rp ".number_format($transaction->amount, 0, ',', '.')."\nNomor WA Pembeli: {$waPembeli}\n\nSilakan segera hubungi pembeli untuk proses penyerahan barang ya! 📦\n\nKlik untuk chat pembeli: {$linkWaPembeli}\n\n_SMEconE Hub_"
            ]);
        } catch (\Exception $e) {
            // Abaikan error
        }
    }

    // 3. Tetap coba kirim sinyal ke Xendit (Opsional)
    try {
        if ($transaction->invoice_id) {
            \Illuminate\Support\Facades\Http::withHeaders(['api-version' => '2022-07-31'])
                ->withBasicAuth(env('XENDIT_SECRET_KEY'), '')
                ->post("https://api.xendit.co/qr_codes/{$transaction->invoice_id}/payments/simulate", [
                    'amount' => (int) $transaction->amount
                ]);
        }
    } catch (\Exception $e) {
        // Abaikan kalau Xendit error
    }

    return "🔥 SUKSES BERAT! Database udah LUNAS & Pesan WA (Pembeli dan Penjual) udah dipicu ke bot. Coba cek terminal bot NodeJS-nya sekarang! Balik ke web dan refresh.";
});