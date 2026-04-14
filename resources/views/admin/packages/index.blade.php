@extends('layouts.admin')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Paket Internet</h1>
            <p class="text-slate-500 mt-1">Kelola paket internet untuk pelanggan.</p>
        </div>

        <button onclick="openCreateModal()"
            class="rounded-xl bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700">
            + Tambah Paket
        </button>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="mb-4 rounded-xl bg-red-100 px-4 py-3 text-red-700">
            {{ $errors->first() }}
        </div>

        <script>
            window.onload = function () {
                openCreateModal();
            };
        </script>
    @endif

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach ($packages as $package)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">

                <h3 class="text-2xl font-bold text-slate-900">
                    {{ $package->name }}
                </h3>

                <span class="mt-2 inline-block rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                    {{ $package->speed_mbps }} Mbps
                </span>

                <div class="mt-5">
                    <p class="text-3xl font-bold text-slate-900">
                        Rp {{ number_format($package->price, 0, ',', '.') }}
                    </p>
                    <p class="text-sm text-slate-500">per bulan</p>
                </div>

                <div class="mt-6 flex gap-2">
                    <button onclick="openEditModal({{ $package->id }}, '{{ $package->name }}', {{ $package->speed_mbps }}, {{ $package->price }})"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-xl text-sm">
                        Edit
                    </button>

                    <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                        onsubmit="return confirm('Hapus paket ini?')">
                        @csrf
                        @method('DELETE')

                        <button class="px-4 py-2 bg-red-500 text-white rounded-xl text-sm">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
</div>

{{-- MODAL --}}
<div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">

        <h2 id="modalTitle" class="text-xl font-bold mb-4">Tambah Paket</h2>

        <form id="form" method="POST">
            @csrf

            <input type="hidden" name="_method" id="methodField">

            <div class="mb-3">
                <input type="text" name="name" id="name"
                    placeholder="Nama Paket"
                    class="w-full border p-3 rounded-xl">
            </div>

            <div class="mb-3">
                <input type="number" name="speed_mbps" id="speed"
                    placeholder="Speed Mbps"
                    class="w-full border p-3 rounded-xl">
            </div>

            <div class="mb-3">
                <input type="number" name="price" id="price"
                    placeholder="Harga"
                    class="w-full border p-3 rounded-xl">
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl">
                    Simpan
                </button>

                <button type="button" onclick="closeModal()"
                    class="border px-4 py-2 rounded-xl">
                    Batal
                </button>
            </div>
        </form>

    </div>
</div>

{{-- SCRIPT --}}
<script>
    function openCreateModal() {
        document.getElementById('modal').classList.remove('hidden');

        document.getElementById('modalTitle').innerText = 'Tambah Paket';
        document.getElementById('form').action = "{{ route('admin.packages.store') }}";
        document.getElementById('methodField').value = '';

        document.getElementById('name').value = '';
        document.getElementById('speed').value = '';
        document.getElementById('price').value = '';
    }

    function openEditModal(id, name, speed, price) {
        document.getElementById('modal').classList.remove('hidden');

        document.getElementById('modalTitle').innerText = 'Edit Paket';
        document.getElementById('form').action = `/admin/packages/${id}`;
        document.getElementById('methodField').value = 'PUT';

        document.getElementById('name').value = name;
        document.getElementById('speed').value = speed;
        document.getElementById('price').value = price;
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>
@endsection