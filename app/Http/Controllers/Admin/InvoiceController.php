<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentConfirmation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $search   = $request->input('search');
        $category = $request->input('category', 'name');
        $statusFilter = $request->input('status_filter');

        $invoiceQuery = Invoice::with(['customer.package', 'customer.user', 'paymentConfirmations'])->latest();

        if ($search) {
            $invoiceQuery->whereHas('customer', function ($q) use ($search, $category) {
                if ($category === 'name') {
                    $q->where('full_name', 'like', "%{$search}%");
                } elseif ($category === 'phone') {
                    $q->where('phone', 'like', "%{$search}%");
                } elseif ($category === 'address') {
                    $q->where('address', 'like', "%{$search}%");
                }
            });

            if ($category === 'invoice_number') {
                $invoiceQuery->where('invoice_number', 'like', "%{$search}%");
            }
        }

        if ($statusFilter) {
            if ($statusFilter === 'pending_confirmation') {
                // invoices that have a pending payment confirmation
                $invoiceQuery->whereHas('paymentConfirmations', function ($q) {
                    $q->where('status', 'submitted');
                });
            } else {
                $invoiceQuery->where('status', $statusFilter);
            }
        }

        $invoices = $invoiceQuery->get();

        $customers = Customer::with(['package', 'user'])
            ->orderBy('full_name')
            ->get();

        $totalInvoices            = Invoice::count();
        $unpaidCount              = Invoice::where('status', 'unpaid')->count();
        $paidCount                = Invoice::where('status', 'paid')->count();
        $pendingConfirmationCount = PaymentConfirmation::where('status', 'submitted')->count();

        return view('admin.invoices.index', compact(
            'customers',
            'invoices',
            'totalInvoices',
            'unpaidCount',
            'paidCount',
            'pendingConfirmationCount'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id'   => ['required', 'exists:customers,id'],
            'billing_month' => ['required', 'integer', 'between:1,12'],
            'billing_year'  => ['required', 'integer', 'min:2024', 'max:2100'],
            'due_date'      => ['required', 'date'],
            'notes'         => ['nullable', 'string'],
        ], [
            'customer_id.required'   => 'Pelanggan wajib dipilih.',
            'billing_month.required' => 'Bulan tagihan wajib dipilih.',
            'billing_year.required'  => 'Tahun tagihan wajib diisi.',
            'due_date.required'      => 'Tanggal jatuh tempo wajib diisi.',
        ]);

        $customer = Customer::with('package')->findOrFail($validated['customer_id']);

        if (! $customer->package) {
            return back()
                ->withErrors(['customer_id' => 'Pelanggan ini belum memiliki paket internet.'])
                ->withInput();
        }

        $existingInvoice = Invoice::where('customer_id', $customer->id)
            ->where('billing_month', $validated['billing_month'])
            ->where('billing_year', $validated['billing_year'])
            ->first();

        if ($existingInvoice) {
            return back()
                ->withErrors(['billing_month' => 'Tagihan untuk pelanggan dan periode tersebut sudah ada.'])
                ->withInput();
        }

        $invoiceDate = Carbon::createFromDate(
            $validated['billing_year'],
            $validated['billing_month'],
            1
        );

        $invoiceNumber = $this->generateInvoiceNumber($invoiceDate);

        Invoice::create([
            'invoice_number'        => $invoiceNumber,
            'customer_id'           => $customer->id,
            'package_name_snapshot' => $customer->package->name,
            'amount'                => $customer->package->price,
            'billing_month'         => $validated['billing_month'],
            'billing_year'          => $validated['billing_year'],
            'due_date'              => $validated['due_date'],
            'status'                => 'unpaid',
            'notes'                 => $validated['notes'] ?? null,
            'created_by'            => Auth::id(),
        ]);

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', "Tagihan pelanggan berhasil dibuat.");
    }

    public function confirm(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== 'paid') {
            $invoice->update([
                'status'      => 'paid',
                'paid_at'     => now(),
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // Also update any pending payment confirmation to approved
            $invoice->paymentConfirmations()
                ->where('status', 'submitted')
                ->update([
                    'status'      => 'approved',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);
        }

        return redirect()
            ->route('admin.invoices.index')
            ->with('success', 'Tagihan berhasil dikonfirmasi sebagai sudah bayar.');
    }

    private function generateInvoiceNumber(Carbon $invoiceDate): string
    {
        $prefix = 'INV-' . $invoiceDate->format('Ym');

        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '-%')
            ->latest('id')
            ->first();

        $lastSequence = 0;

        if ($lastInvoice) {
            $parts        = explode('-', $lastInvoice->invoice_number);
            $lastSequence = (int) end($parts);
        }

        $newSequence = str_pad((string) ($lastSequence + 1), 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $newSequence;
    }
}