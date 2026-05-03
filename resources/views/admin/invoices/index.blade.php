@extends('layouts.admin')

@section('content')
@php
    $monthOptions = [
        1  => 'Januari',
        2  => 'Februari',
        3  => 'Maret',
        4  => 'April',
        5  => 'Mei',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'Agustus',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
@endphp

<div class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-4xl font-bold text-slate-900">Manajemen Pembayaran</h2>
            <p class="mt-2 text-lg text-slate-500">
                Buat tagihan pelanggan dan kelola status pembayaran secara semi manual.
            </p>
        </div>

        <button type="button"
                onclick="openInvoiceModal()"
                class="inline-flex items-center justify-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-600">
            <span class="mr-2 text-lg leading-none">+</span>
            Tambah Penagihan
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>

        <script>
            window.onload = function () {
                openInvoiceModal();
            };
        </script>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Tagihan</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $totalInvoices }}</p>
            <p class="mt-2 text-sm text-slate-500">Seluruh tagihan yang sudah dibuat.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Belum Bayar</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $unpaidCount }}</p>
            <p class="mt-2 text-sm text-slate-500">Tagihan menunggu pembayaran.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Sudah Bayar</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $paidCount }}</p>
            <p class="mt-2 text-sm text-slate-500">Tagihan yang sudah dikonfirmasi lunas.</p>
        </div>

        <a href="{{ route('admin.invoices.index', ['status_filter' => 'pending_confirmation']) }}"
           class="rounded-3xl border {{ request('status_filter') === 'pending_confirmation' ? 'border-amber-400 bg-amber-50' : 'border-amber-200 bg-white' }} p-6 shadow-sm transition hover:border-amber-400 hover:bg-amber-50">
            <p class="text-sm font-medium text-amber-600">Butuh Konfirmasi</p>
            <p class="mt-3 text-4xl font-bold text-amber-700">{{ $pendingConfirmationCount }}</p>
            <p class="mt-2 text-sm text-amber-600">Konfirmasi bayar dari pelanggan.</p>
        </a>
    </div>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Data Penagihan Pelanggan</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar tagihan yang telah dibuat oleh admin.
                </p>
            </div>

            <div class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                {{ $invoices->count() }} tagihan
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="border-b border-slate-200 px-6 py-4">
            <form method="GET" action="{{ route('admin.invoices.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <div class="flex flex-1 overflow-hidden rounded-2xl border border-slate-300 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100">
                    <select name="category"
                            class="border-0 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-0 rounded-l-2xl border-r border-slate-200">
                        <option value="name"           {{ request('category', 'name') === 'name'           ? 'selected' : '' }}>Nama</option>
                        <option value="phone"          {{ request('category') === 'phone'          ? 'selected' : '' }}>No. WA</option>
                        <option value="address"        {{ request('category') === 'address'        ? 'selected' : '' }}>Alamat</option>
                        <option value="invoice_number" {{ request('category') === 'invoice_number' ? 'selected' : '' }}>No. Invoice</option>
                    </select>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari tagihan..."
                           class="flex-1 px-4 py-2.5 text-sm text-slate-900 outline-none">
                </div>

                <select name="status_filter"
                        onchange="this.form.submit()"
                        class="rounded-2xl border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="unpaid"               {{ request('status_filter') === 'unpaid'               ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="paid"                 {{ request('status_filter') === 'paid'                 ? 'selected' : '' }}>Sudah Bayar</option>
                    <option value="pending_confirmation" {{ request('status_filter') === 'pending_confirmation' ? 'selected' : '' }}>Butuh Konfirmasi</option>
                </select>

                <button type="submit"
                        class="rounded-2xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-600">
                    Cari
                </button>
                @if(request('search') || request('status_filter'))
                    <a href="{{ route('admin.invoices.index') }}"
                       class="rounded-2xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if ($invoices->isEmpty())
            <div class="px-6 py-14 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600">
                    💳
                </div>
                <h4 class="mt-5 text-xl font-semibold text-slate-900">
                    {{ request('search') || request('status_filter') ? 'Tagihan tidak ditemukan' : 'Belum ada tagihan' }}
                </h4>
                <p class="mt-2 text-slate-500">
                    {{ request('search') || request('status_filter') ? 'Coba kata kunci atau filter lain.' : 'Buat tagihan pertama untuk pelanggan dari menu tambah penagihan.' }}
                </p>

                @if (!request('search') && !request('status_filter'))
                <button type="button"
                        onclick="openInvoiceModal()"
                        class="mt-6 inline-flex items-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
                    + Tambah Penagihan
                </button>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Pelanggan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Paket</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Bulan Tagihan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Nominal</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach ($invoices as $invoice)
                            @php
                                $hasPendingConfirmation = $invoice->paymentConfirmations->where('status', 'submitted')->isNotEmpty();
                            @endphp
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-4 align-middle">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $invoice->customer->full_name ?? optional($invoice->customer->user)->name ?? '-' }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $invoice->invoice_number }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $invoice->package_name_snapshot }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ optional($invoice->customer->package)->speed_mbps ?? '-' }} Mbps
                                        </p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <p class="font-medium text-slate-800">
                                        {{ $monthOptions[$invoice->billing_month] ?? '-' }} {{ $invoice->billing_year }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <p class="font-semibold text-slate-900">
                                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    @if ($invoice->status === 'paid')
                                        <x-status-badge status="paid">Sudah Bayar</x-status-badge>
                                    @elseif ($hasPendingConfirmation)
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Butuh Konfirmasi
                                        </span>
                                    @else
                                        <x-status-badge status="unpaid">Belum Bayar</x-status-badge>
                                    @endif
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <p class="font-medium text-slate-700">
                                        {{ optional($invoice->due_date)->translatedFormat('d M Y') }}
                                    </p>
                                </td>

                                <td class="px-6 py-4 text-right align-middle">
                                    <div class="inline-flex items-center gap-2">
                                    @if ($invoice->status !== 'paid')
                                        <button type="button"
                                                onclick="openConfirmModal({{ $invoice->id }}, '{{ addslashes($invoice->customer->full_name ?? '-') }}')"
                                                class="rounded-xl bg-green-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-600">
                                            Konfirmasi
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-500">
                                            Terkonfirmasi
                                        </span>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div id="invoiceModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4">
    <div class="w-full max-w-2xl rounded-3xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Tambah Penagihan</h3>
                <p class="mt-1 text-sm text-slate-500">Buat tagihan baru untuk pelanggan.</p>
            </div>

            <button type="button"
                    onclick="closeInvoiceModal()"
                    class="rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">
                ✕
            </button>
        </div>

        <form action="{{ route('admin.invoices.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="customer_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Pelanggan
                    </label>
                    <select id="customer_id"
                            name="customer_id"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->full_name }} - {{ optional($customer->package)->name ?? 'Belum ada paket' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="billing_month" class="mb-2 block text-sm font-semibold text-slate-700">
                        Bulan Tagihan
                    </label>
                    <select id="billing_month"
                            name="billing_month"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required>
                        <option value="">Pilih Bulan</option>
                        @foreach ($monthOptions as $number => $label)
                            <option value="{{ $number }}" {{ old('billing_month', now()->month) == $number ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="billing_year" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tahun Tagihan
                    </label>
                    <input type="number"
                           id="billing_year"
                           name="billing_year"
                           value="{{ old('billing_year', now()->year) }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           min="2024"
                           max="2100"
                           required>
                </div>

                <div>
                    <label for="due_date" class="mb-2 block text-sm font-semibold text-slate-700">
                        Jatuh Tempo
                    </label>
                    <input type="date"
                           id="due_date"
                           name="due_date"
                           value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           required>
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="mb-2 block text-sm font-semibold text-slate-700">
                        Catatan
                    </label>
                    <textarea id="notes"
                              name="notes"
                              rows="3"
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                              placeholder="Opsional, misalnya tagihan bulan berjalan atau catatan tambahan.">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                <button type="submit"
                        class="rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
                    Simpan Tagihan
                </button>

                <button type="button"
                        onclick="closeInvoiceModal()"
                        class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi Pembayaran --}}
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4">
    <div class="w-full max-w-md rounded-3xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <h3 class="text-xl font-bold text-slate-900">Konfirmasi Pembayaran</h3>
            <button type="button" onclick="closeConfirmModal()"
                    class="rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">✕</button>
        </div>
        <div class="px-6 py-5">
            <p class="text-slate-600">Tandai tagihan pelanggan <strong id="confirmCustomerName"></strong> sebagai <span class="font-semibold text-green-600">Sudah Bayar</span>?</p>
            <p class="mt-2 text-sm text-slate-400">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="flex items-center justify-end gap-3 border-t border-slate-100 px-6 py-4">
            <button type="button" onclick="closeConfirmModal()"
                    class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </button>
            <form id="confirmForm" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                <button type="submit"
                        class="rounded-xl bg-green-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-600">
                    Ya, Konfirmasi
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const invoiceModal = document.getElementById('invoiceModal');
    const confirmModal = document.getElementById('confirmModal');
    const confirmForm  = document.getElementById('confirmForm');

    function openInvoiceModal() {
        invoiceModal.classList.remove('hidden');
        invoiceModal.classList.add('flex');
    }

    function closeInvoiceModal() {
        invoiceModal.classList.add('hidden');
        invoiceModal.classList.remove('flex');
    }

    function openConfirmModal(invoiceId, customerName) {
        confirmForm.action = '/admin/invoices/' + invoiceId + '/confirm';
        document.getElementById('confirmCustomerName').textContent = customerName;
        confirmModal.classList.remove('hidden');
        confirmModal.classList.add('flex');
    }

    function closeConfirmModal() {
        confirmModal.classList.add('hidden');
        confirmModal.classList.remove('flex');
    }

    window.addEventListener('click', function (event) {
        if (event.target === invoiceModal)  closeInvoiceModal();
        if (event.target === confirmModal)  closeConfirmModal();
    });
</script>
@endsection