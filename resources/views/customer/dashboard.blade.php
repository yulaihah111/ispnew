@extends('layouts.customer')

@php
    $title = 'Dashboard Pelanggan';
@endphp

@section('content')
<section class="mb-6 rounded-3xl bg-gradient-to-r from-sky-500 to-blue-600 px-7 py-10 text-white shadow-sm">
    <div class="flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
        <div>
            <h2 class="text-4xl font-bold">Selamat Datang, {{ auth()->user()->name }}!</h2>

            <div class="mt-5 flex flex-wrap items-center gap-3 text-lg">
                <span>Premium 50 Mbps</span>
                <x-status-badge status="active" class="bg-green-500 text-white">Aktif</x-status-badge>
            </div>
        </div>

        <div class="flex h-32 w-32 items-center justify-center rounded-full bg-white/15 text-5xl">
            📶
        </div>
    </div>
</section>

<div class="mb-6 grid grid-cols-1 gap-5 md:grid-cols-2">
    <x-page-card class="p-6">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 text-2xl">
                📶
            </div>

            <div>
                <p class="text-sm text-slate-500">Paket Internet</p>
                <h3 class="text-2xl font-semibold text-slate-900">Premium 50 Mbps</h3>
            </div>
        </div>
    </x-page-card>

    <x-page-card class="p-6">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-50 text-green-600 text-2xl">
                ✓
            </div>

            <div>
                <p class="text-sm text-slate-500">Status Layanan</p>
                <h3 class="text-2xl font-semibold text-green-600">Aktif</h3>
            </div>
        </div>
    </x-page-card>
</div>

<x-page-card class="mb-6 p-6">
    <h3 class="mb-6 text-3xl font-bold text-slate-900">Tagihan Aktif</h3>

    <div class="rounded-2xl border border-slate-200 p-5">
        <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm text-slate-500">Invoice ID: INV-005</p>
                <h4 class="mt-2 text-2xl font-bold text-slate-900">Tagihan 10 Mar 2026</h4>
            </div>

            <x-status-badge status="unpaid">Belum Bayar</x-status-badge>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <p class="text-sm text-slate-500">Paket</p>
                <p class="mt-1 text-xl font-semibold text-slate-900">Premium 50 Mbps</p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Jumlah Tagihan</p>
                <p class="mt-1 text-3xl font-bold text-slate-900">Rp 350.000</p>
            </div>
        </div>

        <button type="button" class="w-full rounded-xl bg-sky-500 px-4 py-3 text-base font-semibold text-white transition hover:bg-sky-600">
            Bayar Sekarang
        </button>
    </div>
</x-page-card>

<x-page-card class="p-6">
    <h3 class="mb-6 text-3xl font-bold text-slate-900">Riwayat Pembayaran</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead>
                <tr class="text-sm text-slate-600">
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Invoice ID</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Tanggal Tagihan</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Jumlah</th>
                    <th class="border-b border-slate-200 px-4 py-4 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-b border-slate-100 px-4 py-4 font-bold text-slate-900">INV-001</td>
                    <td class="border-b border-slate-100 px-4 py-4 text-slate-700">10 Feb 2026</td>
                    <td class="border-b border-slate-100 px-4 py-4 font-semibold text-slate-900">Rp 350.000</td>
                    <td class="border-b border-slate-100 px-4 py-4">
                        <x-status-badge status="paid">Lunas</x-status-badge>
                    </td>
                </tr>

                <tr>
                    <td class="px-4 py-4 font-bold text-slate-900">INV-002</td>
                    <td class="px-4 py-4 text-slate-700">10 Jan 2026</td>
                    <td class="px-4 py-4 font-semibold text-slate-900">Rp 350.000</td>
                    <td class="px-4 py-4">
                        <x-status-badge status="paid">Lunas</x-status-badge>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-page-card>
@endsection