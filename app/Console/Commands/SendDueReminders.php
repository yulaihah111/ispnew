<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

class SendDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reminder:send-due';

    /**
     * The console command description.
     */
    protected $description = 'Kirim reminder WhatsApp jatuh tempo tagihan ke semua pelanggan yang belum bayar';

    public function __construct(private ReminderService $reminderService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Memulai pengiriman reminder jatuh tempo via WhatsApp...');

        $results = $this->reminderService->sendDueReminders();

        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Terkirim',  $results['sent']],
                ['⏭️  Dilewati',  $results['skipped']],
                ['❌ Gagal',     $results['failed']],
            ]
        );

        $this->info('✔ Selesai.');

        return Command::SUCCESS;
    }
}
