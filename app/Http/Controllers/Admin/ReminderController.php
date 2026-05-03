<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\WhatsappReminderLog;
use App\Services\ReminderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function __construct(private ReminderService $reminderService) {}

    /**
     * Tampilkan log semua reminder yang pernah dikirim.
     */
    public function index(Request $request): View
    {
        $query = WhatsappReminderLog::with(['invoice', 'customer'])
            ->latest('sent_at');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tipe
        if ($request->filled('type')) {
            $query->where('reminder_type', $request->type);
        }

        // Search
        $search   = $request->input('search');
        $category = $request->input('category', 'name');

        if ($search) {
            if ($category === 'name') {
                $query->whereHas('customer', fn($q) => $q->where('full_name', 'like', "%{$search}%"));
            } elseif ($category === 'phone') {
                $query->where('phone', 'like', "%{$search}%");
            } elseif ($category === 'invoice') {
                $query->whereHas('invoice', fn($q) => $q->where('invoice_number', 'like', "%{$search}%"));
            }
        }

        $logs = $query->paginate(20)->withQueryString();

        // Statistik
        $totalSent   = WhatsappReminderLog::where('status', 'sent')->count();
        $totalFailed = WhatsappReminderLog::where('status', 'failed')->count();
        $totalToday  = WhatsappReminderLog::whereDate('sent_at', today())->count();

        // Invoice belum bayar (untuk tombol kirim manual di halaman ini)
        $unpaidSearch = $request->input('unpaid_search');
        $unpaidQuery  = Invoice::with('customer')
            ->where('status', 'unpaid')
            ->orderBy('due_date');

        if ($unpaidSearch) {
            $unpaidQuery->whereHas('customer', fn($q) => $q->where('full_name', 'like', "%{$unpaidSearch}%"));
        }

        $unpaidInvoices = $unpaidQuery->get();

        return view('admin.reminders.index', compact(
            'logs',
            'totalSent',
            'totalFailed',
            'totalToday',
            'unpaidInvoices'
        ));
    }

    /**
     * Kirim semua reminder yang seharusnya dikirim hari ini.
     */
    public function sendAll(): RedirectResponse
    {
        $results = $this->reminderService->sendDueReminders();

        $message = "Proses selesai: {$results['sent']} terkirim, {$results['skipped']} dilewati, {$results['failed']} gagal.";

        return redirect()
            ->route('admin.reminders.index')
            ->with('success', $message);
    }

    /**
     * Kirim reminder manual untuk satu invoice tertentu.
     */
    public function sendManual(Invoice $invoice): RedirectResponse
    {
        $result = $this->reminderService->sendManual($invoice);

        if ($result['success']) {
            return redirect()
                ->back()
                ->with('success', "Reminder WhatsApp berhasil dikirim ke pelanggan {$invoice->customer?->full_name}.");
        }

        return redirect()
            ->back()
            ->with('error', 'Gagal mengirim reminder: ' . ($result['message'] ?? $result['response'] ?? 'Error tidak diketahui.'));
    }
}
