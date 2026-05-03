@extends('layouts.customer')

@php
    $title = 'Riwayat Tagihan & Transaksi';
@endphp

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-slate-900">Tagihan & Transaksi</h2>
    <p class="mt-2 text-slate-600">Lihat riwayat tagihan dan lakukan konfirmasi pembayaran atas tagihan aktif Anda.</p>
</div>

@if(session('success'))
    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-green-700 font-semibold">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        {{ $errors->first() }}
        <script>
            window.onload = function () {
                openPaymentModal();
            };
        </script>
    </div>
@endif

<x-page-card class="p-6">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead>
                <tr class="text-sm text-slate-600">
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Invoice ID</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Tanggal Jatuh Tempo</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Jumlah</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Status</th>
                    <th class="border-b border-slate-200 px-4 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td class="border-b border-slate-100 px-4 py-4 font-bold text-slate-900">{{ $invoice->invoice_number }}</td>
                    <td class="border-b border-slate-100 px-4 py-4 text-slate-700">{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d M Y') }}</td>
                    <td class="border-b border-slate-100 px-4 py-4 font-semibold text-slate-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                    <td class="border-b border-slate-100 px-4 py-4">
                        <x-status-badge status="{{ $invoice->status }}">
                            {{ ucfirst($invoice->status) }}
                        </x-status-badge>
                    </td>
                    <td class="border-b border-slate-100 px-4 py-4 text-right">
                        @if($invoice->status === 'unpaid')
                            <button onclick="openPaymentModal('{{ $invoice->id }}', '{{ $invoice->invoice_number }}', '{{ $invoice->amount }}')" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-600 transition">
                                Konfirmasi Bayar
                            </button>
                        @elseif($invoice->status === 'pending')
                            <span class="text-sm font-medium text-slate-500">Menunggu Verifikasi</span>
                        @else
                            <span class="text-sm font-medium text-green-600">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-500">Belum ada tagihan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-page-card>

{{-- Modal Pembayaran --}}
<div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4">
    <div class="w-full max-w-lg rounded-3xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Konfirmasi Pembayaran</h3>
                <p class="mt-1 text-sm text-slate-500">Kirim detail transfer Anda untuk <span id="modal_invoice_no" class="font-bold"></span></p>
            </div>
            <button type="button" onclick="closePaymentModal()" class="rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">✕</button>
        </div>

        <form id="paymentForm" action="" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Tanggal Transfer</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 border-slate-300 focus:border-sky-500 focus:ring-sky-100" required>
                </div>
                
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Bank Pengirim</label>
                    <input type="text" name="sender_bank" value="{{ old('sender_bank') }}" placeholder="BCA / Mandiri / BRI / DANA" class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 border-slate-300 focus:border-sky-500 focus:ring-sky-100" required>
                </div>
                
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Nama Pemilik Rekening / Akun</label>
                    <input type="text" name="sender_account_name" value="{{ old('sender_account_name') }}" placeholder="Contoh: Ahmad Wijaya" class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 border-slate-300 focus:border-sky-500 focus:ring-sky-100" required>
                </div>
                
                <div>
                    <label class="mb-1 block text-sm font-semibold text-slate-700">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 border-slate-300 focus:border-sky-500 focus:ring-sky-100" placeholder="Keterangan opsional">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                <button type="submit" class="rounded-2xl bg-sky-500 px-5 py-3 text-sm font-semibold text-white hover:bg-sky-600">Kirim Konfirmasi</button>
                <button type="button" onclick="closePaymentModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    const paymentModal = document.getElementById('paymentModal');
    const paymentForm = document.getElementById('paymentForm');
    const modalInvoiceNo = document.getElementById('modal_invoice_no');
    
    // Simpan route dasar untuk diganti ID
    const basePath = "{{ route('customer.invoices.pay', 'xxx') }}";

    function openPaymentModal(invoice_id = '', invoice_number = '', amount = '') {
        if(invoice_id !== '') {
            paymentForm.action = basePath.replace('xxx', invoice_id);
            modalInvoiceNo.textContent = invoice_number + ' (Rp ' + new Intl.NumberFormat('id-ID').format(amount) + ')';
        }
        paymentModal.classList.remove('hidden');
        paymentModal.classList.add('flex');
    }

    function closePaymentModal() {
        paymentModal.classList.add('hidden');
        paymentModal.classList.remove('flex');
    }

    window.addEventListener('click', function (event) {
        if (event.target === paymentModal) {
            closePaymentModal();
        }
    });
</script>
@endsection
