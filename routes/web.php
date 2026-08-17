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
use App\Http\Controllers\CartController;
use App\Http\Controllers\NotificationController;

Route::get('/404', function() { abort(404); });
Route::get('/403', function() { abort(403); });
Route::get('/419', function() { abort(419); });
Route::get('/500', function() { abort(500); });

// ==========================================
// VERCEL STATIC FILE FALLBACK
// Serve Vite assets manually if Vercel static routing fails
// ==========================================
Route::get('/build/{path}', function ($path) {
    $filePath = public_path('build/' . $path);
    if (file_exists($filePath)) {
        $mime = mime_content_type($filePath);
        if (str_ends_with($filePath, '.css')) $mime = 'text/css';
        if (str_ends_with($filePath, '.js')) $mime = 'application/javascript';
        if (str_ends_with($filePath, '.svg')) $mime = 'image/svg+xml';
        return response()->file($filePath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
    abort(404);
})->where('path', '.*');

Route::get('/debug-vercel', function () {
    $path = public_path();
    $files = [];
    if (is_dir($path)) {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            $files[] = str_replace($path, '', $file->getPathname());
        }
    }
    return response()->json([
        'public_path' => $path,
        'public_exists' => is_dir($path),
        'files' => $files,
    ]);
});

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

// FORGOT PASSWORD
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

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

    Route::get('/kabar', function () {
        $prestasis = \App\Models\Prestasi::with(['likes', 'comments.user'])->get()->map(function($item) {
            $item->feed_type = 'prestasi';
            return $item;
        });
        
        $events = \App\Models\Event::with(['likes', 'comments.user'])->get()->map(function($item) {
            $item->feed_type = 'event';
            return $item;
        });

        $feeds = $prestasis->concat($events)->shuffle();
        
        return view('kabar.index', compact('feeds'));
    })->name('kabar.index');

    Route::post('/interaction/like', [InteractionController::class, 'toggleLike'])->name('like.toggle');
    Route::post('/interaction/comment', [InteractionController::class, 'storeComment'])->name('comment.store');

    // NOTIFIKASI
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifikasi.markAllRead');
    Route::delete('/notifikasi/clear-all', [NotificationController::class, 'clearAll'])->name('notifikasi.clearAll');
    Route::delete('/notifikasi/{id}', [NotificationController::class, 'destroy'])->name('notifikasi.destroy');
    Route::get('/notifikasi/unread-count', [NotificationController::class, 'unreadCount'])->name('notifikasi.unreadCount');

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
    Route::delete('/repository/{id}', [RepositoryController::class, 'destroy']);

    // MARKETPLACE
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/create', [MarketplaceController::class, 'create']);
    Route::get('/marketplace/lapak-saya', [MarketplaceController::class, 'myLapak'])->name('marketplace.lapak');
    Route::get('/marketplace/penjualan', [PaymentController::class, 'salesHistory'])->name('marketplace.sales');
    Route::get('/marketplace/purchases', [App\Http\Controllers\PaymentController::class, 'purchaseHistory'])->name('marketplace.purchases');
    Route::get('/marketplace/rekap-penjualan', [App\Http\Controllers\PaymentController::class, 'salesRecap'])->name('marketplace.recap');
    Route::get('/marketplace/rekap-penjualan/export', [App\Http\Controllers\PaymentController::class, 'exportSalesRecap'])->name('marketplace.recap.export');
    Route::post('/marketplace/register-store', [MarketplaceController::class, 'registerStore']);
    Route::post('/marketplace/store', [MarketplaceController::class, 'store']);
    Route::get('/marketplace/toko/{id}', [MarketplaceController::class, 'toko']);
    
    // Rute untuk Update Profil Toko (Banner & PP)
    Route::post('/marketplace/update-store-profile', [App\Http\Controllers\MarketplaceController::class, 'updateStoreProfile'])->name('marketplace.updateStoreProfile');

    // Rute untuk Update WA Penjual di Lapak
    Route::post('/marketplace/update-wa', [App\Http\Controllers\MarketplaceController::class, 'updateStoreWa'])->name('marketplace.updateWa');

    // PAYMENT SYSTEM
    Route::post('/marketplace/withdraw', [App\Http\Controllers\MarketplaceController::class, 'requestWithdrawal'])->name('marketplace.withdraw');
    
    // TRANSACTION CRUD
    Route::put('/marketplace/transaction/{id}/status', [App\Http\Controllers\PaymentController::class, 'updateTransactionStatus'])->name('marketplace.transaction.status');
    Route::delete('/marketplace/transaction/{id}', [App\Http\Controllers\PaymentController::class, 'destroyTransaction'])->name('marketplace.transaction.destroy');

    // DYNAMIC / ID BASED ROUTES (Must be placed after static routes)
    Route::get('/marketplace/{id}/broadcast', [MarketplaceController::class, 'broadcastPage'])->name('marketplace.broadcast')->where('id', '[0-9]+');
    Route::post('/marketplace/{id}/broadcast', [MarketplaceController::class, 'broadcastKeWa'])->where('id', '[0-9]+');          
    Route::get('/marketplace/payment/{id}', [PaymentController::class, 'paymentStatus'])->name('marketplace.payment.status')->where('id', '[0-9]+');
    Route::get('/marketplace/{id}/checkout', [PaymentController::class, 'checkoutConfirm'])->name('marketplace.checkout.confirm')->where('id', '[0-9]+');
    Route::post('/marketplace/{id}/checkout/direct', [PaymentController::class, 'processDirectPayment'])->name('marketplace.checkout.direct')->where('id', '[0-9]+');
    Route::get('/marketplace/{id}/edit', [MarketplaceController::class, 'edit'])->name('marketplace.edit')->where('id', '[0-9]+');
    Route::put('/marketplace/{id}/update', [MarketplaceController::class, 'update'])->name('marketplace.update')->where('id', '[0-9]+');
    Route::delete('/marketplace/{id}/delete', [MarketplaceController::class, 'destroy'])->where('id', '[0-9]+');
    Route::post('/marketplace/{id}/toggle-sold', [MarketplaceController::class, 'toggleSold'])->where('id', '[0-9]+');
    Route::post('/marketplace/{id}/review', [MarketplaceController::class, 'storeReview'])->where('id', '[0-9]+');
    Route::get('/marketplace/{id}', [MarketplaceController::class, 'show'])->name('marketplace.show')->where('id', '[0-9]+');

    // CART
    Route::get('/keranjang', [CartController::class, 'page'])->name('cart.page');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}/qty', [CartController::class, 'updateQty'])->name('cart.qty');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // FORUM
    Route::get('/forum', [ForumController::class, 'index']);
    Route::post('/forum/{id}/join', [ForumController::class, 'joinChannel']);
    Route::get('/forum/invite/{code}', [ForumController::class, 'joinViaInvite']);
    Route::get('/forum/{id}', [ForumController::class, 'show']);
    Route::post('/forum/{id}/message', [ForumController::class, 'storeMessage']);
    Route::get('/forum/{id}/messages', [ForumController::class, 'fetchMessages']);
    Route::get('/forum/{id}/search', [ForumController::class, 'searchMessages']);
    Route::put('/forum/message/{id}/edit', [ForumController::class, 'editMessage']);
    Route::delete('/forum/message/{id}/delete', [ForumController::class, 'deleteMessage']);
    Route::post('/forum/message/{id}/react', [ForumController::class, 'reactMessage']);
    Route::post('/forum/message/{id}/vote', [ForumController::class, 'votePoll']);

    // ASSIGNMENTS
    Route::get('/forum/{forumThread}/assignment/create', [\App\Http\Controllers\AssignmentController::class, 'create'])->name('assignment.create');
    Route::post('/forum/{forumThread}/assignment', [\App\Http\Controllers\AssignmentController::class, 'store'])->name('assignment.store');
    Route::get('/assignment/{assignment}/edit', [\App\Http\Controllers\AssignmentController::class, 'edit'])->name('assignment.edit');
    Route::put('/assignment/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'update'])->name('assignment.update');
    Route::delete('/assignment/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'destroy'])->name('assignment.destroy');
    Route::get('/assignment/{assignment}/submissions', [\App\Http\Controllers\AssignmentController::class, 'submissions'])->name('assignment.submissions');
    Route::post('/assignment/{assignment}/submit', [\App\Http\Controllers\AssignmentController::class, 'submit'])->name('assignment.submit');
    Route::get('/assignment/{assignment}/export', [\App\Http\Controllers\AssignmentController::class, 'exportAssignmentGrades'])->name('assignment.export');
    Route::post('/submission/{submission}/grade', [\App\Http\Controllers\AssignmentController::class, 'grade'])->name('submission.grade');
    Route::post('/submission/{submission}/toggle-privacy', [\App\Http\Controllers\AssignmentController::class, 'togglePrivacy'])->name('submission.toggle-privacy');

    Route::get('/dashboard/channel', function () { return redirect('/dashboard'); });
    Route::get('/dashboard/channel/create', [ForumController::class, 'createChannel']);
    Route::post('/dashboard/channel', [ForumController::class, 'storeChannel']); 
    Route::get('/dashboard/channel/{id}', [ForumController::class, 'show']);
    Route::get('/dashboard/channel/{forumThread}/export-grades', [AssignmentController::class, 'exportChannelGrades']);
    Route::get('/dashboard/channel/{id}/manage', [ForumController::class, 'manageChannel']);
    Route::post('/dashboard/channel/{id}/request/{requestId}/approve', [ForumController::class, 'approveRequest']);
    Route::post('/dashboard/channel/{id}/request/{requestId}/reject', [ForumController::class, 'rejectRequest']);
    Route::get('/dashboard/channel/{id}/members', [ForumController::class, 'manageChannel']);
    Route::put('/dashboard/channel/{id}', [ForumController::class, 'updateChannel']);
    Route::post('/dashboard/channel/{id}/update', [ForumController::class, 'updateChannel']);
    Route::delete('/dashboard/channel/{id}/delete', [ForumController::class, 'deleteChannel']);
    Route::post('/dashboard/channel/{id}/members', [ForumController::class, 'addMember']);
    Route::delete('/dashboard/channel/{id}/members/{userId}', [ForumController::class, 'removeMember']);

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ZONA ADMIN
Route::middleware(['auth', 'App\Http\Middleware\IsAdmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
    
    Route::get('/admin/prestasi/create', [AdminController::class, 'createPrestasi']);
    Route::post('/admin/prestasi', [AdminController::class, 'storePrestasi']);
    Route::get('/admin/prestasi/{id}/edit', [AdminController::class, 'editPrestasi']);
    Route::put('/admin/prestasi/{id}', [AdminController::class, 'updatePrestasi']);
    Route::delete('/admin/prestasi/{id}/delete', [AdminController::class, 'destroyPrestasi']);
    
    Route::get('/admin/event/create', [AdminController::class, 'createEvent']);
    Route::post('/admin/event', [AdminController::class, 'storeEvent']);
    Route::get('/admin/event/{id}/edit', [AdminController::class, 'editEvent']);
    Route::put('/admin/event/{id}', [AdminController::class, 'updateEvent']);
    Route::delete('/admin/event/{id}/delete', [AdminController::class, 'destroyEvent']);
    
    // WITHDRAWAL
    Route::get('/admin/withdrawals', [AdminController::class, 'withdrawals'])->name('admin.withdrawals');
    Route::post('/admin/withdrawals/{id}/approve', [AdminController::class, 'approveWithdrawal'])->name('admin.withdrawals.approve');
    Route::post('/admin/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdrawal'])->name('admin.withdrawals.reject');
});

// API & WEBHOOK
Route::any('/git/{path}', [GitHttpController::class, 'handle'])->where('path', '.*');
Route::post('/git-hook/{id}/auto-sync', [RepositoryController::class, 'autoSyncGit']);
Route::post('/api/docs/push', [RepositoryController::class, 'pushFromCli'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// WEBHOOK XENDIT
Route::post('/api/xendit/callback', [PaymentController::class, 'handleWebhook'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/tes-bayar/{id}', function($id) {
    $transaction = \App\Models\Transaction::with(['user', 'marketplaceItem', 'marketplaceItem.user'])->find($id);
    
    if(!$transaction) return "Transaksi nggak ketemu bos!";

    $log = [];

    $transaction->update(['status' => 'PAID']);
    $log[] = "✅ Database: Status diubah ke PAID";
    
    if ($transaction->marketplaceItem) {
        if ($transaction->marketplaceItem->stock - $transaction->qty <= 0) {
            $transaction->marketplaceItem->update(['is_sold' => true, 'stock' => 0]);
        } else {
            $transaction->marketplaceItem->decrement('stock', $transaction->qty);
        }
        $log[] = "✅ Stok: Dikurangi " . $transaction->qty;

        $seller = $transaction->marketplaceItem->user;
        if ($seller) {
            $seller->increment('store_balance', $transaction->amount);
            $log[] = "✅ Saldo penjual ditambah";
        }
    }

    $botUrl = 'http://localhost:3000/send-message'; 
    
    if ($transaction->marketplaceItem && $transaction->marketplaceItem->format === 'Digital') {
        $emailTujuan = $transaction->target_email ?? $transaction->user->email;
        $log[] = "📧 Produk DIGITAL terdeteksi! Kirim email ke: " . $emailTujuan;
        $log[] = "📧 Digital Link: " . ($transaction->marketplaceItem->digital_link ?? 'KOSONG');
        
        try {
            \Illuminate\Support\Facades\Mail::to($emailTujuan)->send(new \App\Mail\DigitalProductDelivered($transaction));
            $log[] = "✅ EMAIL BERHASIL DIKIRIM!";
        } catch (\Exception $e) {
            $log[] = "❌ EMAIL GAGAL: " . $e->getMessage();
            \Illuminate\Support\Facades\Log::error('Email digital gagal: ' . $e->getMessage());
        }
    } else {
        $log[] = "📦 Produk FISIK (format: " . ($transaction->marketplaceItem->format ?? 'null') . ") - kirim WA";
        if ($transaction->whatsapp_number) {
            try {
                \Illuminate\Support\Facades\Http::timeout(5)->post($botUrl, [
                    'number' => $transaction->whatsapp_number,
                    'message' => "Halo kak *{$transaction->user->name}*! Pembayaran *{$transaction->marketplaceItem->item_name}* BERHASIL. Penjual akan segera proses. _SMEconE Hub_"
                ]);
                $log[] = "✅ WA Pembeli dikirim";
            } catch (\Exception $e) {
                $log[] = "⚠️ WA Pembeli gagal: " . $e->getMessage();
            }
        }
    }

    $sellerWa = $transaction->marketplaceItem->user->whatsapp_number ?? null;
    if ($sellerWa) {
        try {
            $linkWaPembeli = 'https://wa.me/' . ltrim($transaction->whatsapp_number, '+');
            \Illuminate\Support\Facades\Http::timeout(5)->post($botUrl, [
                'number' => $sellerWa,
                'message' => "🔔 PESANAN BARU! {$transaction->marketplaceItem->item_name} LUNAS oleh {$transaction->user->name}. Chat pembeli: {$linkWaPembeli} _SMEconE Hub_"
            ]);
            $log[] = "✅ WA Penjual dikirim";
        } catch (\Exception $e) {
            $log[] = "⚠️ WA Penjual gagal: " . $e->getMessage();
        }
    }

    try {
        if ($transaction->invoice_id) {
            \Illuminate\Support\Facades\Http::withHeaders(['api-version' => '2022-07-31'])
                ->withBasicAuth(env('XENDIT_SECRET_KEY'), '')
                ->post("https://api.xendit.co/qr_codes/{$transaction->invoice_id}/payments/simulate", [
                    'amount' => (int) $transaction->amount
                ]);
        }
    } catch (\Exception $e) {}

    $output = "<h2>🔥 TES BAYAR - TRANSAKSI #{$id}</h2><hr>";
    foreach ($log as $line) {
        $output .= "<p>{$line}</p>";
    }
    $output .= "<hr><p>Balik ke web dan refresh.</p>";
    return $output;
});