@extends('layouts.admin')

@section('content')
@php
    $totalInvoices = $invoices->count();
    $unpaidInvoices = $invoices->where('status', 'unpaid')->count();
    $paidInvoices = $invoices->where('status', 'paid')->count();

    $monthOptions = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
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

    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Tagihan</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $totalInvoices }}</p>
            <p class="mt-2 text-sm text-slate-500">Seluruh tagihan pelanggan yang sudah dibuat.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Belum Bayar</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $unpaidInvoices }}</p>
            <p class="mt-2 text-sm text-slate-500">Tagihan yang masih menunggu pembayaran.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Sudah Bayar</p>
            <p class="mt-3 text-4xl font-bold text-slate-900">{{ $paidInvoices }}</p>
            <p class="mt-2 text-sm text-slate-500">Tagihan yang sudah dikonfirmasi lunas.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Data Penagihan Pelanggan</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar tagihan yang telah dibuat oleh admin.
                </p>
            </div>

            <div class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                {{ $totalInvoices }} tagihan
            </div>
        </div>

        @if ($invoices->isEmpty())
            <div class="px-6 py-14 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600">
                    💳
                </div>
                <h4 class="mt-5 text-xl font-semibold text-slate-900">Belum ada tagihan</h4>
                <p class="mt-2 text-slate-500">
                    Buat tagihan pertama untuk pelanggan dari menu tambah penagihan.
                </p>

                <button type="button"
                        onclick="openInvoiceModal()"
                        class="mt-6 inline-flex items-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
                    + Tambah Penagihan
                </button>
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
                                    @if ($invoice->status !== 'paid')
                                        <form action="{{ route('admin.invoices.confirm', $invoice->id) }}"
                                              method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Konfirmasi tagihan ini sebagai sudah bayar?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="rounded-xl bg-green-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-600">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-500">
                                            Terkonfirmasi
                                        </span>
                                    @endif
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

<script>
    const invoiceModal = document.getElementById('invoiceModal');

    function openInvoiceModal() {
        invoiceModal.classList.remove('hidden');
        invoiceModal.classList.add('flex');
    }

    function closeInvoiceModal() {
        invoiceModal.classList.add('hidden');
        invoiceModal.classList.remove('flex');
    }

    window.addEventListener('click', function (event) {
        if (event.target === invoiceModal) {
            closeInvoiceModal();
        }
    });
</script>
@endsection