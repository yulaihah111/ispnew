@extends('layouts.admin')

@section('content')
@php
    $totalCustomers = $customers->count();
    $activePackages = $customers->pluck('package_id')->filter()->unique()->count();
    $latestCustomer = $customers->first();
@endphp

<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-4xl font-bold tracking-tight text-slate-900">Data Pelanggan</h2>
            <p class="mt-2 text-lg text-slate-500">
                Kelola akun pelanggan dan hubungkan ke paket internet yang tersedia.
            </p>
        </div>

        <button type="button"
                onclick="openCustomerModal()"
                class="inline-flex items-center justify-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-600">
            <span class="mr-2 text-lg leading-none">+</span>
            Tambah Pelanggan
        </button>
    </div>

    {{-- Alert --}}
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
                openCustomerModal();
            };
        </script>
    @endif
    
</div>

    {{-- Main table card --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Daftar Pelanggan</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Data akun pelanggan beserta paket internet yang dipilih.
                </p>
            </div>

            <div class="rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">
                {{ $totalCustomers }} pelanggan
            </div>
        </div>

        @if ($customers->isEmpty())
            <div class="px-6 py-14 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600">
                    👥
                </div>
                <h4 class="mt-5 text-xl font-semibold text-slate-900">Belum ada pelanggan</h4>
                <p class="mt-2 text-slate-500">
                    Tambahkan pelanggan pertama untuk mulai mengelola akun dan paket internet.
                </p>

                <button type="button"
                        onclick="openCustomerModal()"
                        class="mt-6 inline-flex items-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
                    + Tambah Pelanggan
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Pelanggan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Kontak</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Paket</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Harga</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach ($customers as $customer)
                            <tr class="transition hover:bg-slate-50/80">
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-sm font-bold uppercase text-blue-700">
                                            {{ strtoupper(substr(optional($customer->user)->name ?? 'U', 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">
                                                {{ optional($customer->user)->name ?? '-' }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                ID Pelanggan #{{ $customer->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <p class="font-medium text-slate-700">
                                        {{ optional($customer->user)->email ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Akun login pelanggan
                                    </p>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ optional($customer->package)->name ?? '-' }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ optional($customer->package)->speed_mbps ?? '-' }} Mbps
                                        </p>
                                    </div>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <p class="font-semibold text-slate-900">
                                        Rp {{ number_format(optional($customer->package)->price ?? 0, 0, ',', '.') }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">per bulan</p>
                                </td>

                                <td class="px-6 py-4 align-middle">
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right align-middle">
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Modal Tambah Pelanggan --}}
<div id="customerModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4">
    <div class="w-full max-w-2xl rounded-3xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Tambah Pelanggan</h3>
                <p class="mt-1 text-sm text-slate-500">Buat akun user lalu hubungkan ke paket internet.</p>
            </div>

            <button type="button"
                    onclick="closeCustomerModal()"
                    class="rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">
                ✕
            </button>
        </div>

        <form action="{{ route('admin.customers.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label for="customer_name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Pelanggan
                    </label>
                    <input type="text"
                           id="customer_name"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: Ahmad Wijaya"
                           required>
                </div>

                <div class="md:col-span-2">
                    <label for="customer_email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Email
                    </label>
                    <input type="email"
                           id="customer_email"
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: ahmad@krisnanet.test"
                           required>
                </div>

                <div>
                    <label for="customer_password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Password
                    </label>
                    <input type="password"
                           id="customer_password"
                           name="password"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Minimal 6 karakter"
                           required>
                </div>

                <div>
                    <label for="customer_package_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Paket Internet
                    </label>
                    <select id="customer_package_id"
                            name="package_id"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required>
                        <option value="">Pilih Paket</option>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} - {{ $package->speed_mbps }} Mbps - Rp {{ number_format($package->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                <button type="submit"
                        class="rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
                    Simpan Pelanggan
                </button>

                <button type="button"
                        onclick="closeCustomerModal()"
                        class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const customerModal = document.getElementById('customerModal');

    function openCustomerModal() {
        customerModal.classList.remove('hidden');
        customerModal.classList.add('flex');
    }

    function closeCustomerModal() {
        customerModal.classList.add('hidden');
        customerModal.classList.remove('flex');
    }

    window.addEventListener('click', function (event) {
        if (event.target === customerModal) {
            closeCustomerModal();
        }
    });
</script>
@endsection