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
    public function checkoutConfirm($id)
    {
        $item = Marketplace::findOrFail($id);
        return view('marketplace.checkout_confirm', compact('item'));
    }

    // 2. FUNGSI UNTUK MEMPROSES API XENDIT
    public function processDirectPayment(Request $request, $id)
    {
        // Validasi ditambah wajib isi nomor WA
        $request->validate([
            'payment_method' => 'required|in:QRIS,DANA,GOPAY',
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

        // Tambahkan whatsapp_number ke database
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'marketplace_item_id' => $item->id,
            'amount' => $item->price,
            'whatsapp_number' => $waNumber, 
            'status' => 'PENDING',
        ]);

        $secretKey = env('XENDIT_SECRET_KEY');

        // JIKA USER PILIH QRIS
        if ($method === 'QRIS') {
            $response = Http::withHeaders([
                    'api-version' => '2022-07-31' 
                ])
                ->withBasicAuth($secretKey, '')
                ->post('https://api.xendit.co/qr_codes', [
                    'reference_id' => 'TRX-' . $transaction->id,
                    'type' => 'DYNAMIC',
                    'currency' => 'IDR',
                    'amount' => (int) $item->price, 
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
        // JIKA USER PILIH DANA ATAU GOPAY
        else { 
            $channelCode = 'ID_' . $method;

            $response = Http::withBasicAuth($secretKey, '')
                ->post('https://api.xendit.co/ewallets/charges', [
                    'reference_id' => 'TRX-' . $transaction->id,
                    'currency' => 'IDR',
                    'amount' => (int) $item->price,
                    'checkout_method' => 'ONE_TIME_PAYMENT',
                    'channel_code' => $channelCode,
                    'channel_properties' => [
                        'success_redirect_url' => route('marketplace.index'), 
                        'failure_redirect_url' => route('marketplace.index'),
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $transaction->update(['invoice_id' => $data['id']]); 
                
                $checkoutUrl = null;
                if(isset($data['actions'])) {
                    foreach ($data['actions'] as $action) {
                        if (in_array($action['action'], ['MOBILE_WEB_CHECKOUT_URL', 'DESKTOP_WEB_CHECKOUT_URL'])) {
                            $checkoutUrl = $action['url'];
                            break;
                        }
                    }
                }

                if ($checkoutUrl) {
                    return redirect($checkoutUrl);
                }
            } else {
                $errorData = $response->json();
                $pesanError = $errorData['message'] ?? 'Unknown Error';
                $detail = isset($errorData['errors']) ? json_encode($errorData['errors']) : '';
                return back()->with('error', 'Gagal E-Wallet: ' . $pesanError . ' | Detail: ' . $detail);
            }
        }

        return back()->with('error', 'Terjadi kesalahan sistem.');
    }

    // 3. FUNGSI UNTUK MENAMPILKAN HALAMAN STATUS QRIS AUTO-REFRESH
    public function paymentStatus($id)
    {
        $transaction = Transaction::findOrFail($id);
        $item = $transaction->marketplaceItem;
        
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
        
        // Cek e-Wallet
        if (isset($payload['data']['id']) && isset($payload['event']) && str_contains($payload['event'], 'ewallet')) {
            $transaction = Transaction::with(['user', 'marketplaceItem'])->where('invoice_id', $payload['data']['id'])->first();
            if ($transaction && $payload['data']['status'] === 'SUCCEEDED') {
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
            $transaction->marketplaceItem->update(['is_sold' => true]);

            // ==========================================
            // TRIGGER WHATSAPP BOT AUTO-REPLY
            // ==========================================
            if ($transaction->whatsapp_number) {
                try {
                    // Sesuaikan URL ini dengan endpoint API WA Bot lokal/online milikmu
                    $botUrl = 'http://localhost:3000/send-message'; 
                    
                    Http::post($botUrl, [
                        'number' => $transaction->whatsapp_number,
                        'message' => "Halo kak {$transaction->user->name}! 🛒\n\nPembayaran untuk *{$transaction->marketplaceItem->title}* sebesar Rp ".number_format($transaction->amount, 0, ',', '.')." sudah *BERHASIL* kami terima.\n\nPenjual akan segera memproses pesanan kakak. Terima kasih! 🚀"
                    ]);
                } catch (\Exception $e) {
                    // Biarkan kosong agar error bot tidak mengganggu proses lunas Xendit
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}