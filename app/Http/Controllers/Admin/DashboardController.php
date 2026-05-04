<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentConfirmation;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCustomers = Customer::count();

        $unpaidInvoices = Invoice::where('status', 'unpaid')->count();

        $pendingPayments = PaymentConfirmation::where('status', 'submitted')->count();

        $paidThisMonth = Invoice::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->count();

        $recentInvoices = Invoice::with('customer')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'unpaidInvoices',
            'pendingPayments',
            'paidThisMonth',
            'recentInvoices'
        ));
    }
}