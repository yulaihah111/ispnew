<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $customer = auth()->user()->customer;
        
        $activeInvoice = \App\Models\Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['unpaid', 'pending'])
            ->latest('due_date')
            ->first();

        $recentInvoices = \App\Models\Invoice::where('customer_id', $customer->id)
            ->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('customer', 'activeInvoice', 'recentInvoices'));
    }
}