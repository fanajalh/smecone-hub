<?php
// Check latest transaction state
$t = \App\Models\Transaction::with('marketplaceItem')->latest()->first();
echo "=== LATEST TRANSACTION ===\n";
echo "ID: {$t->id}\n";
echo "Status: {$t->status}\n";
echo "Target Email: " . ($t->target_email ?? 'NULL') . "\n";
echo "User Email: " . ($t->user->email ?? 'NULL') . "\n";
echo "Item Format: " . ($t->marketplaceItem->format ?? 'NULL') . "\n";
echo "Item Name: " . ($t->marketplaceItem->item_name ?? 'NULL') . "\n";
echo "Digital Link: " . ($t->marketplaceItem->digital_link ?? 'NULL') . "\n";
echo "\n=== ALL RECENT TRANSACTIONS ===\n";
$txns = \App\Models\Transaction::with('marketplaceItem')->latest()->take(5)->get();
foreach ($txns as $tx) {
    echo "TRX#{$tx->id} | Status:{$tx->status} | Email:{$tx->target_email} | Item:{$tx->marketplaceItem->item_name} | Format:{$tx->marketplaceItem->format}\n";
}

// Now try sending the email manually and catch errors
echo "\n=== TESTING EMAIL SEND ===\n";
try {
    $emailTo = $t->target_email ?? $t->user->email;
    echo "Sending to: {$emailTo}\n";
    
    if ($t->marketplaceItem->format !== 'Digital') {
        echo "PROBLEM: Item format is '{$t->marketplaceItem->format}' NOT 'Digital'! Email won't be triggered.\n";
    }
    
    \Illuminate\Support\Facades\Mail::to($emailTo)->send(new \App\Mail\DigitalProductDelivered($t));
    echo "EMAIL SENT SUCCESSFULLY!\n";
} catch (\Exception $e) {
    echo "EMAIL FAILED: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
