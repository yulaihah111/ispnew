@extends('layouts.admin')

@section('content')
@php
    $totalCustomers = $customers->count();
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
<div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm mt-8">
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

    {{-- Search Bar --}}
    <div class="border-b border-slate-200 px-6 py-4">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="flex flex-1 overflow-hidden rounded-2xl border border-slate-300 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100">
                <select name="category"
                        class="border-0 bg-slate-50 px-3 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-0 rounded-l-2xl border-r border-slate-200">
                    <option value="name"    {{ request('category', 'name') === 'name'    ? 'selected' : '' }}>Nama</option>
                    <option value="phone"   {{ request('category') === 'phone'   ? 'selected' : '' }}>No. WA</option>
                    <option value="address" {{ request('category') === 'address' ? 'selected' : '' }}>Alamat</option>
                </select>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari pelanggan..."
                       class="flex-1 px-4 py-2.5 text-sm text-slate-900 outline-none">
            </div>
            <button type="submit"
                    class="rounded-2xl bg-blue-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-600">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.customers.index') }}"
                   class="rounded-2xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    @if ($customers->isEmpty())
        <div class="px-6 py-14 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600">
                👥
            </div>
            <h4 class="mt-5 text-xl font-semibold text-slate-900">
                {{ request('search') ? 'Pelanggan tidak ditemukan' : 'Belum ada pelanggan' }}
            </h4>
            <p class="mt-2 text-slate-500">
                {{ request('search') ? 'Coba kata kunci lain atau reset pencarian.' : 'Tambahkan pelanggan pertama untuk mulai mengelola akun dan paket internet.' }}
            </p>

            @if (!request('search'))
            <button type="button"
                    onclick="openCustomerModal()"
                    class="mt-6 inline-flex items-center rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
                + Tambah Pelanggan
            </button>
            @endif
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Pelanggan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">No. WA</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600">Alamat</th>
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
                                            {{ optional($customer->user)->email ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <p class="font-medium text-slate-700">
                                    {{ $customer->phone ?? '-' }}
                                </p>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <p class="text-sm text-slate-700 max-w-[180px] truncate" title="{{ $customer->address ?? '-' }}">
                                    {{ $customer->address ?? '-' }}
                                </p>
                                @if($customer->district && $customer->district !== '-')
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $customer->district }}</p>
                                @endif
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
                                <div class="inline-flex items-center gap-2">
                                    <button type="button"
                                            onclick="openEditModal(
                                                {{ $customer->id }},
                                                '{{ addslashes(optional($customer->user)->name ?? '') }}',
                                                '{{ addslashes(optional($customer->user)->email ?? '') }}',
                                                '{{ addslashes($customer->phone ?? '') }}',
                                                '{{ addslashes($customer->address ?? '') }}',
                                                '{{ addslashes($customer->district ?? '') }}',
                                                {{ $customer->package_id ?? 'null' }}
                                            )"
                                            class="rounded-xl border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-600 transition hover:bg-blue-50">
                                        Edit
                                    </button>

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
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ====== Modal Tambah Pelanggan ====== --}}
<div id="customerModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
    <div class="w-full max-w-2xl rounded-3xl bg-white shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 sticky top-0 bg-white rounded-t-3xl z-10">
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
                {{-- Nama --}}
                <div class="md:col-span-2">
                    <label for="customer_name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Pelanggan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="customer_name"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                           placeholder="Contoh: Ahmad Wijaya"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="md:col-span-2">
                    <label for="customer_email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="customer_email"
                           name="email"
                           value="{{ old('email') }}"
                           class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 {{ $errors->has('email') ? 'border-red-500 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                           placeholder="Contoh: ahmad@krisnanet.test"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="customer_password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password"
                           id="customer_password"
                           name="password"
                           class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                           placeholder="Minimal 6 karakter"
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Paket --}}
                <div>
                    <label for="customer_package_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Paket Internet <span class="text-red-500">*</span>
                    </label>
                    <select id="customer_package_id"
                            name="package_id"
                            class="w-full rounded-2xl border px-4 py-3 text-slate-900 outline-none focus:ring-2 {{ $errors->has('package_id') ? 'border-red-500 focus:border-red-500 focus:ring-red-100' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-100' }}"
                            required>
                        <option value="">Pilih Paket</option>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}" {{ old('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} – {{ $package->speed_mbps }} Mbps – Rp {{ number_format($package->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('package_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No. WhatsApp --}}
                <div>
                    <label for="customer_phone" class="mb-2 block text-sm font-semibold text-slate-700">
                        No. WhatsApp
                    </label>
                    <input type="text"
                           id="customer_phone"
                           name="phone"
                           value="{{ old('phone', '+62 811-4701-927') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: +62 811-4701-927">
                </div>

                {{-- Kecamatan --}}
                <div>
                    <label for="customer_district" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kecamatan
                    </label>
                    <input type="text"
                           id="customer_district"
                           name="district"
                           value="{{ old('district') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: Lowokwaru">
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label for="customer_address" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alamat
                    </label>
                    <textarea id="customer_address"
                              name="address"
                              rows="2"
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                              placeholder="Contoh: Jl. Soekarno-Hatta No. 12">{{ old('address') }}</textarea>
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

{{-- ====== Modal Edit Pelanggan (Lengkap) ====== --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
    <div class="w-full max-w-2xl rounded-3xl bg-white shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 sticky top-0 bg-white rounded-t-3xl z-10">
            <div>
                <h3 class="text-2xl font-bold text-slate-900">Edit Pelanggan</h3>
                <p class="mt-1 text-sm text-slate-500">Perbarui data pelanggan yang dipilih.</p>
            </div>
            <button type="button"
                    onclick="closeEditModal()"
                    class="rounded-xl px-3 py-2 text-slate-500 hover:bg-slate-100">
                ✕
            </button>
        </div>

        <form id="editForm" method="POST" class="p-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                {{-- Nama Pelanggan --}}
                <div class="md:col-span-2">
                    <label for="edit_full_name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama Pelanggan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="edit_full_name"
                           name="full_name"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: Ahmad Wijaya"
                           required>
                </div>

                {{-- Email --}}
                <div class="md:col-span-2">
                    <label for="edit_email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="edit_email"
                           name="email"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: ahmad@krisnanet.test"
                           required>
                </div>

                {{-- No. WA --}}
                <div>
                    <label for="edit_phone" class="mb-2 block text-sm font-semibold text-slate-700">
                        No. WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="edit_phone"
                           name="phone"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: +62 811-4701-927"
                           required>
                </div>

                {{-- Kecamatan --}}
                <div>
                    <label for="edit_district" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kecamatan
                    </label>
                    <input type="text"
                           id="edit_district"
                           name="district"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Contoh: Lowokwaru">
                </div>

                {{-- Alamat --}}
                <div class="md:col-span-2">
                    <label for="edit_address" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alamat
                    </label>
                    <textarea id="edit_address"
                              name="address"
                              rows="2"
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                              placeholder="Contoh: Jl. Soekarno-Hatta No. 12"></textarea>
                </div>

                {{-- Password (opsional) --}}
                <div>
                    <label for="edit_password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Password
                        <span class="text-xs font-normal text-slate-400">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password"
                           id="edit_password"
                           name="password"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                           placeholder="Minimal 6 karakter">
                </div>

                {{-- Paket Internet --}}
                <div>
                    <label for="edit_package_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Paket Internet <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_package_id"
                            name="package_id"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            required>
                        <option value="">Pilih Paket</option>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}">
                                {{ $package->name }} – {{ $package->speed_mbps }} Mbps – Rp {{ number_format($package->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-3">
                <button type="submit"
                        class="rounded-2xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-600">
                    Simpan Perubahan
                </button>
                <button type="button"
                        onclick="closeEditModal()"
                        class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const customerModal = document.getElementById('customerModal');
    const editModal     = document.getElementById('editModal');
    const editForm      = document.getElementById('editForm');

    function openCustomerModal() {
        customerModal.classList.remove('hidden');
        customerModal.classList.add('flex');
    }

    function closeCustomerModal() {
        customerModal.classList.add('hidden');
        customerModal.classList.remove('flex');
    }

    function openEditModal(customerId, fullName, email, phone, address, district, packageId) {
        editForm.action = '/admin/customers/' + customerId + '/update-contact';

        document.getElementById('edit_full_name').value = fullName;
        document.getElementById('edit_email').value     = email;
        document.getElementById('edit_phone').value     = phone;
        document.getElementById('edit_address').value   = address;
        document.getElementById('edit_district').value  = district;
        document.getElementById('edit_password').value  = '';

        const pkgSelect = document.getElementById('edit_package_id');
        for (let opt of pkgSelect.options) {
            opt.selected = (parseInt(opt.value) === packageId);
        }

        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    }

    window.addEventListener('click', function (event) {
        if (event.target === customerModal) closeCustomerModal();
        if (event.target === editModal)     closeEditModal();
    });
</script>
@endsection