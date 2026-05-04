@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-slate-900">Dashboard</h2>
    <p class="mt-2 text-xl text-slate-500">Selamat datang di sistem manajemen ISP Krisna Net</p>
</div>

<div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Total Pelanggan</p>
        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $totalCustomers }}</h3>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Belum Bayar</p>
        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $unpaidInvoices }}</h3>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Menunggu Verifikasi</p>
        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $pendingPayments }}</h3>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm text-slate-500">Lunas Bulan Ini</p>
        <h3 class="mt-2 text-3xl font-bold text-slate-900">{{ $paidThisMonth }}</h3>
    </div>
</div>

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
    <h3 class="mb-8 text-2xl font-bold text-slate-900">Pembayaran Terbaru</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead>
                <tr class="text-lg text-slate-700">
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">ID Invoice</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Pelanggan</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Paket</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Jumlah</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Status</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Tanggal</th>
                </tr>
            </thead>

            <tbody class="text-lg text-slate-700">
                @forelse($recentInvoices as $invoice)
                    <tr>
                        <td class="border-b border-slate-100 px-4 py-5 font-bold text-slate-900">
                            {{ $invoice->invoice_number }}
                        </td>

                        <td class="border-b border-slate-100 px-4 py-5">
                            {{ $invoice->customer?->full_name ?? '-' }}
                        </td>

                        <td class="border-b border-slate-100 px-4 py-5">
                            {{ $invoice->package_name_snapshot ?? '-' }}
                        </td>

                        <td class="border-b border-slate-100 px-4 py-5 font-bold text-slate-900">
                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                        </td>

                        <td class="border-b border-slate-100 px-4 py-5">
                            @if($invoice->status === 'paid')
                                <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Lunas
                                </span>
                            @elseif(in_array($invoice->status, ['pending', 'submitted']))
                                <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">
                                    Menunggu Verifikasi
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Belum Bayar
                                </span>
                            @endif
                        </td>

                        <td class="border-b border-slate-100 px-4 py-5">
                            {{ optional($invoice->created_at)->translatedFormat('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">
                            Belum ada data invoice.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection