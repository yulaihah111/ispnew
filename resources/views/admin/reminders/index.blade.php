@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    <div>
        <h2 class="text-4xl font-bold tracking-tight text-slate-900">Reminder WhatsApp</h2>
        <p class="mt-2 text-lg text-slate-500">
            Klik tombol Kirim WA untuk membuka WhatsApp dengan template tagihan otomatis.
        </p>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
            <h3 class="text-lg font-bold text-slate-900">Tagihan Belum Lunas</h3>
            <p class="mt-1 text-sm text-slate-500">
                Daftar invoice unpaid yang bisa dikirim reminder manual satu per satu.
            </p>
        </div>

        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
            <form method="GET" action="{{ route('admin.reminders.index') }}" class="flex flex-col gap-3 md:flex-row">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama pelanggan atau nomor WhatsApp..."
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
                >

                <button
                    type="submit"
                    class="rounded-2xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Cari
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.reminders.index') }}"
                       class="rounded-2xl border border-slate-200 px-6 py-3 text-center text-sm font-semibold text-slate-700 hover:bg-white">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Pelanggan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Invoice</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Paket</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Nominal</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">No. WA</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($unpaidInvoices as $invoice)
                        @php
                            $customer = $invoice->customer;

                            $rawPhone = preg_replace('/[^0-9]/', '', $customer?->phone ?? '');

                            if (str_starts_with($rawPhone, '0')) {
                                $waPhone = '62' . substr($rawPhone, 1);
                            } elseif (str_starts_with($rawPhone, '62')) {
                                $waPhone = $rawPhone;
                            } else {
                                $waPhone = '62' . $rawPhone;
                            }

                            $message = "Halo {$customer?->full_name},\n\n"
                                . "Kami dari " . config('app.name', 'Krisna Net') . " ingin mengingatkan bahwa tagihan internet Anda belum lunas.\n\n"
                                . "Detail tagihan:\n"
                                . "No. Invoice: {$invoice->invoice_number}\n"
                                . "Paket: {$invoice->package_name_snapshot}\n"
                                . "Nominal: Rp " . number_format($invoice->amount, 0, ',', '.') . "\n"
                                . "Jatuh Tempo: " . optional($invoice->due_date)->translatedFormat('d F Y') . "\n\n"
                                . "Mohon segera lakukan pembayaran agar layanan internet tetap aktif.\n\n"
                                . "Jika sudah melakukan pembayaran, silakan kirim bukti transfer melalui chat ini.\n\n"
                                . "Terima kasih.";

                            $waUrl = 'https://wa.me/' . $waPhone . '?text=' . urlencode($message);
                        @endphp

                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $customer?->full_name ?? '-' }}</div>
                                <div class="text-xs text-slate-500">{{ $customer?->customer_code ?? '-' }}</div>
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $invoice->invoice_number }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $invoice->package_name_snapshot }}
                            </td>

                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ optional($invoice->due_date)->translatedFormat('d F Y') }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $customer?->phone ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                @if($rawPhone)
                                    <a href="{{ $waUrl }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        Kirim WA
                                    </a>
                                @else
                                    <span class="text-sm text-red-500">No. WA kosong</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
                                Tidak ada tagihan belum lunas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($unpaidInvoices->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $unpaidInvoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection