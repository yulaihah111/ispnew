<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $query = Invoice::with('customer')
            ->where('status', 'unpaid')
            ->orderBy('due_date');

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $unpaidInvoices = $query->paginate(15)->withQueryString();

        return view('admin.reminders.index', compact('unpaidInvoices'));
    }
}