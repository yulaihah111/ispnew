<?php

use App\Console\Commands\SendDueReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler Reminder WhatsApp Jatuh Tempo
|--------------------------------------------------------------------------
| Setiap hari pukul 08:00 WIB, sistem akan otomatis memeriksa semua tagihan
| yang belum dibayar dan mengirim reminder ke nomor WhatsApp pelanggan.
|
| Pastikan Task Scheduler / Cron Job sudah dikonfigurasi:
|   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
*/
// Schedule::command(SendDueReminders::class)
//     ->dailyAt('08:00')
//     ->timezone('Asia/Jakarta')
//     ->withoutOverlapping()
//     ->appendOutputTo(storage_path('logs/reminder.log'));

