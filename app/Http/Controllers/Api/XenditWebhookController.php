<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
{
    $data = $request->all();
    
    // Cari transaksi berdasarkan ID dari Xendit
    $transaction = Transaction::where('invoice_id', $data['id'])->first();

    if ($transaction && ($data['status'] === 'SETTLED' || $data['status'] === 'PAID')) {
        $transaction->update(['status' => 'PAID']);
        
        // Opsional: Tandai barang di marketplace sebagai "Terjual"
        $transaction->marketplaceItem->update(['is_sold' => true]);

        // Send Notification to Seller
        if ($transaction->marketplaceItem->user_id !== $transaction->user_id) {
            \App\Models\AppNotification::send(
                $transaction->marketplaceItem->user_id,
                'marketplace',
                'Barangmu Terjual!',
                'Hore! ' . $transaction->marketplaceItem->item_name . ' berhasil terjual.',
                ['url' => '/marketplace/lapak-saya']
            );
        }
    }

    return response()->json(['status' => 'success']);
}
}
