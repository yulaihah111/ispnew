<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentConfirmation;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $invoices = Invoice::where('customer_id', $customer->id)
            ->latest()
            ->get();

        return view('customer.invoices.index', compact('invoices'));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        // Ensure invoice belongs to the current user
        if ($invoice->customer_id !== auth()->user()->customer->id) {
            abort(403);
        }

        // Validate
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'sender_bank' => 'required|string|max:255',
            'sender_account_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Create Payment Confirmation
        PaymentConfirmation::create([
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_id,
            'payment_date' => $validated['payment_date'],
            'sender_bank' => $validated['sender_bank'],
            'sender_account_name' => $validated['sender_account_name'],
            'notes' => $validated['notes'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('customer.invoices.index')
            ->with('success', 'Bukti pembayaran berhasil dikirim dan sedang menunggu verifikasi.');
    }
}
