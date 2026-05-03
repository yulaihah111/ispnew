<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\WhatsappReminderLog;

$log = WhatsappReminderLog::whereDate('sent_at', today())->first();

if ($log) {
    echo "Status: " . $log->status . "\n";
    echo "Response: " . $log->response . "\n";
} else {
    echo "No log found for today.\n";
}
