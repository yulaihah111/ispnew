<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $query = Customer::with(['user', 'package'])->latest();

        $search   = request('search');
        $category = request('category', 'name');

        if ($search) {
            if ($category === 'name') {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
                });
            } elseif ($category === 'phone') {
                $query->where('phone', 'like', "%{$search}%");
            } elseif ($category === 'address') {
                $query->where('address', 'like', "%{$search}%");
            }
        }

        $customers = $query->get();
        $packages  = Package::all();

        return view('admin.customers.index', compact('customers', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'package_id' => 'required|exists:packages,id',
            'phone'      => 'nullable|string|max:30',
            'address'    => 'nullable|string|max:500',
            'district'   => 'nullable|string|max:100',
        ]);

        // 1. Buat user login
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 2. Buat customer
        Customer::create([
            'user_id'           => $user->id,
            'package_id'        => $validated['package_id'],
            'customer_code'     => 'CUST-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
            'full_name'         => $validated['name'],
            'phone'             => $validated['phone'] ?? '+62 811-4701-927',
            'address'           => $validated['address'] ?? '-',
            'district'          => $validated['district'] ?? '-',
            'service_status'    => 'active',
            'installation_date' => now(),
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Update data lengkap pelanggan (dari modal edit).
     * Dipanggil via route: PATCH /admin/customers/{customer}/update-contact
     */
    public function updateContact(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'full_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $customer->user_id,
            'phone'      => 'required|string|max:30',
            'address'    => 'nullable|string|max:500',
            'district'   => 'nullable|string|max:100',
            'password'   => 'nullable|min:6',
            'package_id' => 'required|exists:packages,id',
        ]);

        // Update user (nama & email)
        if ($customer->user) {
            $customer->user()->update([
                'name'  => $validated['full_name'],
                'email' => $validated['email'],
            ]);

            // Update password jika diisi
            if (!empty($validated['password'])) {
                $customer->user()->update([
                    'password' => Hash::make($validated['password']),
                ]);
            }
        }

        // Update data customer
        $customer->update([
            'full_name'  => $validated['full_name'],
            'phone'      => $validated['phone'],
            'address'    => $validated['address'] ?? '-',
            'district'   => $validated['district'] ?? '-',
            'package_id' => $validated['package_id'],
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->user) {
            $customer->user()->delete();
        }
        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}