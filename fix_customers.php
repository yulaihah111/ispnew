<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orphanedUsers = App\Models\User::where('role', 'customer')->doesntHave('customer')->get();

foreach ($orphanedUsers as $user) {
    App\Models\Customer::create([
        'user_id' => $user->id,
        'package_id' => 2, // Assuming package_id 2 exists as standard
        'customer_code' => 'CUST-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
        'full_name' => $user->name,
        'phone' => '-',
        'address' => '-',
        'district' => '-',
        'service_status' => 'active',
        'installation_date' => now(),
    ]);
    echo "Fixed data for user: " . $user->name . "\n";
}

echo "All missing customers have been fixed!\n";
