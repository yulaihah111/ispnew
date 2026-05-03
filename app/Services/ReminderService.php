<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\WhatsappReminderLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ReminderService
{
    public function __construct(private WhatsappService $whatsapp) {}

    /**
     * Cek semua invoice belum bayar dan kirim reminder sesuai kondisi.
     * Dipanggil oleh scheduler setiap hari.
     */
    public function sendDueReminders(): array
    {
        $today = Carbon::today();
        $results = ['sent' => 0, 'skipped' => 0, 'failed' => 0];

        $invoices = Invoice::with('customer')
            ->where('status', 'unpaid')
            ->get();

        foreach ($invoices as $invoice) {
            $customer = $invoice->customer;

            if (!$customer || empty($customer->phone)) {
                Log::warning("[Reminder] Invoice #{$invoice->invoice_number}: pelanggan tidak punya nomor HP.");
                $results['skipped']++;
                continue;
            }

            $dueDate   = Carbon::parse($invoice->due_date);
            $diffDays  = (int) $today->diffInDays($dueDate, false); // positif = belum jatuh tempo

            // Tentukan tipe reminder
            $reminderType = match (true) {
                $diffDays === 3  => '3_days_before',
                $diffDays === 0  => 'due_date',
                $diffDays === -1 => 'overdue_1_day',
                default          => null,
            };

            if ($reminderType === null) {
                $results['skipped']++;
                continue;
            }

            // Cek duplikasi: sudah pernah kirim tipe ini untuk invoice ini hari ini?
            $alreadySent = WhatsappReminderLog::where('invoice_id', $invoice->id)
                ->where('reminder_type', $reminderType)
                ->whereDate('sent_at', $today)
                ->exists();

            if ($alreadySent) {
                Log::info("[Reminder] Sudah kirim {$reminderType} untuk invoice #{$invoice->invoice_number}. Skip.");
                $results['skipped']++;
                continue;
            }

            $message = $this->buildMessage($invoice, $reminderType);
            $result  = $this->whatsapp->send($customer->phone, $message);

            WhatsappReminderLog::create([
                'invoice_id'    => $invoice->id,
                'customer_id'   => $customer->id,
                'phone'         => $customer->phone,
                'reminder_type' => $reminderType,
                'message'       => $message,
                'status'        => $result['success'] ? 'sent' : 'failed',
                'sent_at'       => now(),
                'response'      => $result['response'],
            ]);

            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
            }
        }

        Log::info('[Reminder] Selesai.', $results);
        return $results;
    }

    /**
     * Kirim reminder manual untuk satu invoice.
     */
    public function sendManual(Invoice $invoice): array
    {
        $customer = $invoice->customer;

        if (!$customer || empty($customer->phone)) {
            return ['success' => false, 'message' => 'Pelanggan tidak memiliki nomor HP.'];
        }

        $message = $this->buildMessage($invoice, 'manual');
        $result  = $this->whatsapp->send($customer->phone, $message);

        WhatsappReminderLog::create([
            'invoice_id'    => $invoice->id,
            'customer_id'   => $customer->id,
            'phone'         => $customer->phone,
            'reminder_type' => 'manual',
            'message'       => $message,
            'status'        => $result['success'] ? 'sent' : 'failed',
            'sent_at'       => now(),
            'response'      => $result['response'],
        ]);

        return $result;
    }

    /**
     * Bangun teks pesan reminder berdasarkan tipe.
     */
    private function buildMessage(Invoice $invoice, string $type): string
    {
        $customer   = $invoice->customer;
        $nama       = $customer->full_name ?? 'Pelanggan';
        $invoice_no = $invoice->invoice_number;
        $paket      = $invoice->package_name_snapshot;
        $nominal    = 'Rp ' . number_format($invoice->amount, 0, ',', '.');
        $jatuhTempo = Carbon::parse($invoice->due_date)->translatedFormat('d F Y');
        $appName    = config('app.name', 'Krisna Net');

        $intro = match ($type) {
            '3_days_before' =>
                "Kami mengingatkan bahwa tagihan internet Anda akan jatuh tempo dalam *3 hari lagi* 📅",
            'due_date' =>
                "Tagihan internet Anda *jatuh tempo HARI INI* ⚠️ Mohon segera lakukan pembayaran.",
            'overdue_1_day' =>
                "Tagihan internet Anda telah *melewati jatuh tempo* sejak kemarin 🔴 Harap segera bayar untuk menghindari pemutusan layanan.",
            default =>
                "Berikut adalah informasi tagihan internet Anda.",
        };

        return <<<MSG
Halo *{$nama}* 👋

{$intro}

📋 *Detail Tagihan:*
• No. Invoice  : {$invoice_no}
• Paket        : {$paket}
• Jumlah       : {$nominal}
• Jatuh Tempo  : {$jatuhTempo}

Segera lakukan pembayaran untuk menghindari gangguan layanan internet.

Terima kasih 🙏
*{$appName}*
MSG;
    }
}
