<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Invoice;
use Carbon\Carbon;

$today = Carbon::today();
$invoices = Invoice::with('customer')->where('status', 'unpaid')->get();

foreach ($invoices as $invoice) {
    $customer = $invoice->customer;
    if (!$customer || empty($customer->phone)) {
        echo "Skip: No phone\n";
        continue;
    }

    $dueDate = Carbon::parse($invoice->due_date);
    $diffDays = $today->diffInDays($dueDate, false);
    
    echo "Invoice ID: {$invoice->id}, Due: {$invoice->due_date}, Diff: ";
    var_dump($diffDays);
    
    $reminderType = match (true) {
        $diffDays === 3  => '3_days_before',
        $diffDays === 0  => 'due_date',
        $diffDays === -1 => 'overdue_1_day',
        default          => null,
    };
    
    echo "Reminder Type: " . ($reminderType ?? 'null') . "\n";
    
    if ($reminderType !== null) {
        $alreadySent = App\Models\WhatsappReminderLog::where('invoice_id', $invoice->id)
            ->where('reminder_type', $reminderType)
            ->whereDate('sent_at', $today)
            ->exists();
        echo "Already sent: " . ($alreadySent ? 'yes' : 'no') . "\n";
    }
}
