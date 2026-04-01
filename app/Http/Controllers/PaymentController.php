<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marketplace;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // 1. FUNGSI UNTUK MENAMPILKAN HALAMAN PILIH METODE BAYAR
    public function checkoutConfirm(Request $request, $id)
    {
        $item = Marketplace::findOrFail($id);
        $qty = $request->query('qty', 1);
        return view('marketplace.checkout_confirm', compact('item', 'qty'));
    }

    // 2. FUNGSI UNTUK MEMPROSES API XENDIT
    public function processDirectPayment(Request $request, $id)
    {
        // Validasi ditambah wajib isi nomor WA
        $request->validate([
            'payment_method' => 'required|in:QRIS,DANA,GOPAY,COD',
            'whatsapp_number' => 'required'
        ]);

        // ==========================================
        // AUTO-FORMAT NOMOR WA KE 62
        // ==========================================
        $waNumber = $request->whatsapp_number;
        
        // Hapus semua karakter yang bukan angka
        $waNumber = preg_replace('/[^0-9]/', '', $waNumber);
        
        // Kalau depannya angka 0, potong 0-nya ganti jadi 62
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        } 
        // Kalau depannya bukan 62, tambahin 62
        elseif (!str_starts_with($waNumber, '62')) {
            $waNumber = '62' . $waNumber;
        }
        // ==========================================

        $method = $request->payment_method;
        $item = Marketplace::findOrFail($id);
        
        $qty = $request->input('qty', 1);
        $totalAmount = $item->price * $qty;

        // Validasi Stok
        $stock = $item->stock ?? 999;
        if ($item->is_sold || $qty > $stock) {
            return back()->with('error', 'Maaf, stok barang tidak mencukupi atau sudah habis terjual.');
        }

        // Tambahkan whatsapp_number ke database
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'marketplace_item_id' => $item->id,
            'qty' => $qty,
            'amount' => $totalAmount,
            'whatsapp_number' => $waNumber, 
            'payment_method' => $method,
            'status' => 'PENDING',
        ]);

        $secretKey = env('XENDIT_SECRET_KEY');
        $timeout = 30; // Timeout 30 detik untuk mengantisipasi cURL error 28

        // JIKA USER PILIH QRIS
        if ($method === 'QRIS') {
            $response = Http::timeout($timeout)->withHeaders([
                    'api-version' => '2022-07-31' 
                ])
                ->withBasicAuth($secretKey, '')
                ->post('https://api.xendit.co/qr_codes', [
                    'reference_id' => 'TRX-' . $transaction->id,
                    'type' => 'DYNAMIC',
                    'currency' => 'IDR',
                    'amount' => (int) $totalAmount, 
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Simpan ID QRIS & Text URL QR-nya ke database
                $transaction->update([
                    'invoice_id' => $data['id'],
                    'invoice_url' => $data['qr_string'] 
                ]); 
                
                // Redirect ke halaman yang bisa Auto-Refresh
                return redirect()->route('marketplace.payment.status', $transaction->id);
            } else {
                $errorData = $response->json();
                $pesanError = $errorData['message'] ?? 'Unknown Error';
                $detail = isset($errorData['errors']) ? json_encode($errorData['errors']) : '';
                return back()->with('error', 'Gagal QRIS: ' . $pesanError . ' | Detail: ' . $detail);
            }
        } 
        // JIKA USER PILIH E-WALLET (DANA & GOPAY DIGABUNG PAKAI API V2 TERBARU)
        elseif (in_array($method, ['DANA', 'GOPAY'])) {
            $response = Http::timeout($timeout)->withHeaders([
                'api-version' => '2022-07-31',
                // Otomatis mengirim URL Ngrok kamu yang sedang aktif ke Xendit
                'x-callback-url' => env('XENDIT_WEBHOOK_URL', url('/api/xendit/callback'))
            ])->withBasicAuth($secretKey, '')
                ->post('https://api.xendit.co/payment_requests', [
                    'reference_id' => 'TRX-' . $transaction->id,
                    'currency' => 'IDR',
                    'amount' => (int) $totalAmount,
                    'payment_method' => [
                        'type' => 'EWALLET',
                        'reusability' => 'ONE_TIME_USE',
                        'ewallet' => [
                            'channel_code' => $method, // Otomatis jadi 'DANA' atau 'GOPAY'
                            'channel_properties' => [
                                'success_return_url' => route('marketplace.payment.status', $transaction->id),
                                'failure_return_url' => route('marketplace.index'),
                                'cancel_return_url'  => route('marketplace.index'), // Wajib ada untuk GoPay & DANA v2
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $transaction->update(['invoice_id' => $data['id']]); 
                
                $checkoutUrl = null;
                if(isset($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (isset($action['url'])) {
                            $checkoutUrl = $action['url'];
                            break;
                        }
                    }
                }

                if ($checkoutUrl) {
                    $transaction->update(['invoice_url' => $checkoutUrl]);
                    // Lempar ke halaman status E-Wallet kita
                    return redirect()->route('marketplace.payment.status', $transaction->id);
                }
            } else {
                $errorData = $response->json();
                $pesanError = $errorData['message'] ?? 'Unknown Error';
                $detail = isset($errorData['errors']) ? json_encode($errorData['errors']) : '';
                return back()->with('error', 'Gagal E-Wallet ('.$method.'): ' . $pesanError . ' | Detail: ' . $detail);
            }
        }
        // JIKA USER PILIH COD
        elseif ($method === 'COD') {
            $transaction->update([
                'status' => 'DIPROSES' // Langsung diproses tanpa melalui payment gateway
            ]);
            
            // Pengurangan stok
            if ($item->stock - $qty <= 0) {
                $item->update([
                    'is_sold' => true,
                    'stock' => 0
                ]);
            } else {
                $item->decrement('stock', $qty);
            }

            // ==========================================
            // TRIGGER WHATSAPP BOT (KE PEMBELI & PENJUAL)
            // ==========================================
            $botUrl = 'http://localhost:3000/send-message'; 
            
            // 1. Notif ke Pembeli
            try {
                \Illuminate\Support\Facades\Http::timeout(5)->post($botUrl, [
                    'number' => $transaction->whatsapp_number,
                    'message' => "Halo kak *{$transaction->user->name}*! 🤝\n\nPesanan COD untuk *{$item->item_name}* sebesar *Rp ".number_format($totalAmount, 0, ',', '.')."* telah kami terima.\n\nPenjual akan segera memproses pesanan dan menghubungi kakak untuk janjian. Mohon tunggu ya! 🚀\n\n_SMEconE Hub_"
                ]);
            } catch (\Exception $e) {}

            // 2. Notif ke Penjual
            if ($item->user) {
                $sellerWa = $item->user->whatsapp_number;
                if ($sellerWa) {
                    try {
                        $waPembeli = str_starts_with($transaction->whatsapp_number, '62') ? '+' . $transaction->whatsapp_number : $transaction->whatsapp_number;
                        $linkWaPembeli = 'https://wa.me/' . ltrim($transaction->whatsapp_number, '+');

                        \Illuminate\Support\Facades\Http::timeout(5)->post($botUrl, [
                            'number' => $sellerWa,
                            'message' => "🔔 *PESANAN COD BARU!* 🔔\n\nHalo! Ada pesanan dengan metode COD (Bayar di Tempat) untuk barangmu *{$item->item_name}* dari *{$transaction->user->name}*.\n\n*Detail Pesanan:*\nNominal: Rp ".number_format($totalAmount, 0, ',', '.')."\nNomor WA Pembeli: {$waPembeli}\n\nSilakan segera hubungi pembeli untuk deal lokasi ketemuan dan penyerahan barang ya! 🤝\n\nKlik untuk chat pembeli: {$linkWaPembeli}\n\n_SMEconE Hub_"
                        ]);
                    } catch (\Exception $e) {}
                }
            }

            return redirect()->route('marketplace.purchases')->with('success', 'Pesanan COD berhasil dibuat! Penjual akan segera menghubungi Anda.');
        }

        return back()->with('error', 'Terjadi kesalahan sistem.');
    }

    // 3. FUNGSI UNTUK MENAMPILKAN HALAMAN STATUS AUTO-REFRESH
    public function paymentStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        $item = $transaction->marketplaceItem;
        
        // Cek jika ini E-Wallet (biasanya invoice_url diawali http dari URL checkout Xendit)
        if ($transaction->invoice_url && str_starts_with($transaction->invoice_url, 'http')) {
             $paymentMethod = 'E-Wallet';
             // Deteksi channel code dari Xendit Payment Requests API v2 (Karena DANA & GOPAY digabung ke v2)
             if (str_starts_with($transaction->invoice_id, 'pr-')) {
                 $secretKey = env('XENDIT_SECRET_KEY');
                 $response = \Illuminate\Support\Facades\Http::withHeaders(['api-version' => '2022-07-31'])
                     ->withBasicAuth($secretKey, '')
                     ->get('https://api.xendit.co/payment_requests/' . $transaction->invoice_id);
                     
                 if ($response->successful()) {
                     $prData = $response->json();
                     $paymentMethod = strtoupper($prData['payment_method']['ewallet']['channel_code'] ?? 'E-Wallet');
                 }
             }

             return view('marketplace.ewallet_payment', compact('item', 'transaction', 'paymentMethod'));
        }
        
        return view('marketplace.qris_payment', compact('item', 'transaction'));
    }

    // 4. FUNGSI UNTUK HALAMAN RIWAYAT PENJUALAN LAPAK SAYA
    public function salesHistory()
    {
        $sales = Transaction::with(['user', 'marketplaceItem'])
            ->whereHas('marketplaceItem', function($q) {
                $q->where('user_id', Auth::id()); 
            })->latest()->get();

        return view('marketplace.sales_history', compact('sales'));
    }

    // 5. FUNGSI WEBHOOK UNTUK MENERIMA SINYAL DARI XENDIT / NGROK
    public function handleWebhook(Request $request) 
    {
        $payload = $request->all();
        $isPaid = false;
        $transaction = null;
        
        // Cek e-Wallet (Legacy API - jaga-jaga kalau masih ada transaksi nyangkut)
        if (isset($payload['data']['id']) && isset($payload['event']) && str_contains($payload['event'], 'ewallet')) {
            $transaction = Transaction::with(['user', 'marketplaceItem'])->where('invoice_id', $payload['data']['id'])->first();
            if ($transaction && $payload['data']['status'] === 'SUCCEEDED') {
                $isPaid = true;
            }
        }
        // Cek Payment Request v2 (DANA & GOPAY yang baru)
        elseif (isset($payload['event']) && $payload['event'] === 'payment.succeeded' && isset($payload['data']['reference_id'])) {
            $transaction = Transaction::with(['user', 'marketplaceItem'])
                ->where('id', str_replace('TRX-', '', $payload['data']['reference_id']))
                ->first();
            if ($transaction) {
                $isPaid = true;
            }
        }
        // Cek QRIS
        elseif (isset($payload['qr_id']) && isset($payload['status']) && $payload['status'] === 'COMPLETED') {
            $transaction = Transaction::with(['user', 'marketplaceItem'])->where('invoice_id', $payload['qr_id'])->first();
            if ($transaction) {
                $isPaid = true;
            }
        }

        // Jika transaksi terdeteksi Lunas
        if ($isPaid && $transaction) {
            $transaction->update(['status' => 'PAID']);
            
            // Pengurangan stok
            if ($transaction->marketplaceItem) {
                if ($transaction->marketplaceItem->stock - $transaction->qty <= 0) {
                    $transaction->marketplaceItem->update([
                        'is_sold' => true,
                        'stock' => 0
                    ]);
                } else {
                    $transaction->marketplaceItem->decrement('stock', $transaction->qty);
                }

                // Penambahan saldo lapak penjual
                $seller = $transaction->marketplaceItem->user;
                if ($seller) {
                    $seller->increment('store_balance', $transaction->amount);
                }
            }

            // ==========================================
            // TRIGGER WHATSAPP BOT (KE PEMBELI & PENJUAL)
            // ==========================================
            $botUrl = 'http://localhost:3000/send-message'; 
            
            // 1. Notif ke Pembeli
            if ($transaction->whatsapp_number && $transaction->marketplaceItem) {
                try {
                    Http::timeout(5)->post($botUrl, [
                        'number' => $transaction->whatsapp_number,
                        'message' => "Halo kak *{$transaction->user->name}*! 🛒\n\nPembayaran kakak untuk pesanan *{$transaction->marketplaceItem->item_name}* sebesar *Rp ".number_format($transaction->amount, 0, ',', '.')."* telah kami terima dan *BERHASIL*.\n\nPenjual akan segera memproses pesanan kakak. Mohon kesediaannya untuk menunggu ya. Terima kasih! 🚀\n\n_SMEconE Hub_"
                    ]);
                } catch (\Exception $e) {
                    // Abaikan error agar tidak mengganggu proses lunas Xendit
                }
            }

            // 2. Notif ke Penjual
            if ($transaction->marketplaceItem && $transaction->marketplaceItem->user) {
                $sellerWa = $transaction->marketplaceItem->user->whatsapp_number;
                if ($sellerWa) {
                    try {
                        $waPembeli = str_starts_with($transaction->whatsapp_number, '62') ? '+' . $transaction->whatsapp_number : $transaction->whatsapp_number;
                        $linkWaPembeli = 'https://wa.me/' . ltrim($transaction->whatsapp_number, '+');

                        Http::timeout(5)->post($botUrl, [
                            'number' => $sellerWa,
                            'message' => "🔔 *PESANAN BARU MASUK!* 🔔\n\nSelamat! Barang jualanmu *{$transaction->marketplaceItem->item_name}* telah LUNAS dibayar oleh *{$transaction->user->name}*.\n\n*Detail Pesanan:*\nNominal: Rp ".number_format($transaction->amount, 0, ',', '.')."\nNomor WA Pembeli: {$waPembeli}\n\nSilakan segera hubungi pembeli untuk proses penyerahan barang ya! 📦\n\nKlik untuk chat pembeli: {$linkWaPembeli}\n\n_SMEconE Hub_"
                        ]);
                    } catch (\Exception $e) {
                        // Abaikan error agar tidak mengganggu proses lunas Xendit
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // FUNGSI UNTUK HALAMAN RIWAYAT PEMBELIAN (USER)
    public function purchaseHistory()
    {
        $purchases = Transaction::with(['marketplaceItem.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('marketplace.purchase_history', compact('purchases'));
    }

    // FUNGSI UNTUK UPDATE STATUS TRANSAKSI (CRUD)
    public function updateTransactionStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:DIBATALKAN,DIPROSES,SELESAI'
        ]);

        $newStatus = $request->status;

        // Jika user adalah PEMBELI
        if ($transaction->user_id === Auth::id()) {
            if ($newStatus === 'DIBATALKAN' && $transaction->status === 'PENDING') {
                $transaction->update(['status' => 'DIBATALKAN']);
                return back()->with('success', 'Pesanan berhasil dibatalkan.');
            }
            return back()->with('error', 'Pembeli hanya dapat membatalkan pesanan yang belum dibayar.');
        }

        // Jika user adalah PENJUAL
        if ($transaction->marketplaceItem->user_id === Auth::id()) {
            if (in_array($newStatus, ['DIPROSES', 'SELESAI']) && in_array($transaction->status, ['PAID', 'DIPROSES'])) {
                $transaction->update(['status' => $newStatus]);
                return back()->with('success', 'Status pesanan berhasil diperbarui menjadi ' . $newStatus);
            }
            return back()->with('error', 'Hanya pesanan PAID/DIPROSES yang dapat diubah statusnya.');
        }

        return abort(403, 'Anda tidak berhak mengubah transaksi ini.');
    }

    // FUNGSI UNTUK MENGHAPUS RIWAYAT TRANSAKSI
    public function destroyTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Cek Otorisasi (Apakah Pembeli atau Penjual)
        $isBuyer = $transaction->user_id === Auth::id();
        $isSeller = $transaction->marketplaceItem->user_id === Auth::id();

        if (!$isBuyer && !$isSeller) {
            return abort(403, 'Akses ditolak.');
        }

        // Aturan: Hanya boleh Hapus jika statusnya DIBATALKAN atau SELESAI
        if (in_array($transaction->status, ['DIBATALKAN', 'SELESAI', 'PENDING'])) {
            $transaction->delete(); // Hard delete
            return back()->with('success', 'Riwayat transaksi berhasil dihapus.');
        }

        return back()->with('error', 'Transaksi yang sedang berjalan tidak dapat dihapus.');
    }
}