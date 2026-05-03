<?php
// Script cek invoice unpaid dan due date status
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Invoice;
use Carbon\Carbon;

$invoices = Invoice::with('customer')->where('status', 'unpaid')->get();

if ($invoices->isEmpty()) {
    echo "Tidak ada invoice unpaid.\n";
    exit;
}

echo str_pad('Invoice', 22) . str_pad('Due Date', 14) . str_pad('Diff', 8) . str_pad('Phone', 16) . "\n";
echo str_repeat('-', 65) . "\n";

foreach ($invoices as $inv) {
    $diff  = Carbon::today()->diffInDays(Carbon::parse($inv->due_date)->startOfDay(), false);
    $phone = optional($inv->customer)->phone ?? '(kosong)';
    echo str_pad($inv->invoice_number, 22)
       . str_pad($inv->due_date, 14)
       . str_pad($diff . ' hari', 8)
       . str_pad($phone, 16)
       . "\n";
}
