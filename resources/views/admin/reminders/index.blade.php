@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-4xl font-bold tracking-tight text-slate-900">Reminder WhatsApp</h2>
            <p class="mt-2 text-lg text-slate-500">
                Kelola dan pantau pengiriman reminder jatuh tempo tagihan via WhatsApp.
            </p>
        </div>

        <form action="{{ route('admin.reminders.send-all') }}" method="POST"
              onsubmit="return confirm('Kirim semua reminder yang seharusnya dikirim hari ini?')">
            @csrf
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-green-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-600">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                Kirim Reminder Sekarang
            </button>
        </form>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            <i class="fa-solid fa-circle-check mt-0.5 text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <i class="fa-solid fa-circle-xmark mt-0.5 text-red-500"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-600 text-xl">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Terkirim</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $totalSent }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-100 text-red-600 text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Gagal</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $totalFailed }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 text-xl">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-500">Dikirim Hari Ini</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $totalToday }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tagihan Belum Bayar (Kirim Manual) --}}
    @if ($unpaidInvoices->isNotEmpty())
    <div class="overflow-hidden rounded-3xl border border-amber-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-amber-100 bg-amber-50 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-lg font-bold text-amber-900">
                    <i class="fa-solid fa-clock mr-2 text-amber-500"></i>
                    Tagihan Belum Lunas — Kirim Reminder Manual
                </h3>
                <p class="mt-1 text-sm text-amber-700">
                    {{ $unpaidInvoices->count() }} tagihan aktif. Klik tombol untuk kirim reminder langsung ke pelanggan.
                </p>
            </div>
        </div>

        {{-- Search Unpaid --}}
        <div class="border-b border-amber-100 bg-amber-50/50 px-6 py-3">
            <form method="GET" action="{{ route('admin.reminders.index') }}" class="flex items-center gap-3">
                @foreach(request()->except('unpaid_search') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <div class="flex flex-1 max-w-md overflow-hidden rounded-xl border border-amber-200 bg-white focus-within:border-amber-400 focus-within:ring-2 focus-within:ring-amber-100">
                    <input type="text"
                           name="unpaid_search"
                           value="{{ request('unpaid_search') }}"
                           placeholder="Cari nama pelanggan..."
                           class="flex-1 px-4 py-2 text-sm text-slate-900 outline-none">
                </div>
                <button type="submit"
                        class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                    Cari
                </button>
                @if(request('unpaid_search'))
                    <a href="{{ route('admin.reminders.index', request()->except('unpaid_search')) }}"
                       class="rounded-xl border border-amber-200 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-50">
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
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">No. Invoice</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Nominal</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">No. WhatsApp</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($unpaidInvoices as $inv)
                        @php
                            $daysLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($inv->due_date)->startOfDay(), false);
                            $isOverdue = $daysLeft < 0;
                        @endphp
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-4 align-middle">
                                <p class="font-semibold text-slate-900">{{ $inv->customer->full_name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <p class="font-mono text-sm text-slate-700">{{ $inv->invoice_number }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <p class="font-semibold text-slate-900">Rp {{ number_format($inv->amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div>
                                    <p class="font-medium {{ $isOverdue ? 'text-red-600' : 'text-slate-700' }}">
                                        {{ \Carbon\Carbon::parse($inv->due_date)->translatedFormat('d M Y') }}
                                    </p>
                                    @if ($isOverdue)
                                        <span class="mt-1 inline-block rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-600">
                                            Terlambat {{ abs($daysLeft) }} hari
                                        </span>
                                    @elseif ($daysLeft <= 3)
                                        <span class="mt-1 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-600">
                                            {{ $daysLeft }} hari lagi
                                        </span>
                                    @else
                                        <span class="mt-1 inline-block rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">
                                            {{ $daysLeft }} hari lagi
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <p class="text-sm text-slate-600">{{ $inv->customer->phone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right align-middle">
                                @if ($inv->customer && $inv->customer->phone)
                                    <form action="{{ route('admin.reminders.send-manual', $inv->id) }}" method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Kirim reminder WhatsApp ke {{ $inv->customer->full_name }}?')">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-xl bg-green-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-600">
                                            <i class="fa-brands fa-whatsapp"></i>
                                            Kirim WA
                                        </button>
                                    </form>
                                @else
                                    <span class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-400">
                                        No HP kosong
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Log Pengiriman --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Riwayat Pengiriman Reminder</h3>
                <p class="mt-1 text-sm text-slate-500">Log semua pesan WhatsApp yang pernah dikirim oleh sistem.</p>
            </div>

            {{-- Filter --}}
            <form method="GET" class="flex items-center gap-2" id="filterForm">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <select name="status"
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 outline-none focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="sent"   {{ request('status') === 'sent'   ? 'selected' : '' }}>Terkirim</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
                </select>
                <select name="type"
                        onchange="this.form.submit()"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700 outline-none focus:border-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="3_days_before" {{ request('type') === '3_days_before' ? 'selected' : '' }}>H-3</option>
                    <option value="due_date"      {{ request('type') === 'due_date'      ? 'selected' : '' }}>Hari H</option>
                    <option value="overdue_1_day" {{ request('type') === 'overdue_1_day' ? 'selected' : '' }}>H+1</option>
                    <option value="manual"        {{ request('type') === 'manual'        ? 'selected' : '' }}>Manual</option>
                </select>
            </form>
        </div>

        {{-- Search Log --}}
        <div class="border-b border-slate-200 px-6 py-4">
            <form method="GET" action="{{ route('admin.reminders.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @foreach(request()->only(['status', 'type']) as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <div class="flex flex-1 overflow-hidden rounded-2xl border border-slate-300 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100">
                    <select name="category"
                            class="border-0 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-0 rounded-l-2xl border-r border-slate-200">
                        <option value="name"    {{ request('category', 'name') === 'name'    ? 'selected' : '' }}>Nama Pelanggan</option>
                        <option value="phone"   {{ request('category') === 'phone'   ? 'selected' : '' }}>No. WA</option>
                        <option value="invoice" {{ request('category') === 'invoice' ? 'selected' : '' }}>No. Invoice</option>
                    </select>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari log reminder..."
                           class="flex-1 px-4 py-2.5 text-sm text-slate-900 outline-none">
                </div>
                <button type="submit"
                        class="rounded-2xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-600">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.reminders.index', request()->except(['search', 'category', 'page'])) }}"
                       class="rounded-2xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        @if ($logs->isEmpty())
            <div class="px-6 py-14 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-green-50 text-2xl text-green-600">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h4 class="mt-5 text-xl font-semibold text-slate-900">Belum ada riwayat</h4>
                <p class="mt-2 text-slate-500">Reminder belum pernah dikirim atau filter tidak cocok.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Pelanggan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Invoice</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">No. WA</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Tipe</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Waktu Kirim</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($logs as $log)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-4 align-middle">
                                    <p class="font-semibold text-slate-900">{{ $log->customer->full_name ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <p class="font-mono text-sm text-slate-600">{{ $log->invoice->invoice_number ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <p class="text-sm text-slate-600">{{ $log->phone }}</p>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    @php
                                        $typeColor = match ($log->reminder_type) {
                                            '3_days_before' => 'bg-blue-100 text-blue-700',
                                            'due_date'      => 'bg-amber-100 text-amber-700',
                                            'overdue_1_day' => 'bg-red-100 text-red-700',
                                            default         => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $typeColor }}">
                                        {{ $log->reminderTypeLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    @if ($log->status === 'sent')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                            <i class="fa-solid fa-check"></i> Terkirim
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">
                                            <i class="fa-solid fa-xmark"></i> Gagal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-middle">
                                    <p class="text-sm text-slate-600">
                                        {{ $log->sent_at?->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') ?? '-' }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
