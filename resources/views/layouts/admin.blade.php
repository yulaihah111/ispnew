@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-slate-100 flex">
    <aside class="w-72 shrink-0 border-r border-slate-200 bg-white min-h-screen">
        <div class="flex items-center gap-4 px-6 py-6">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-500 text-2xl font-bold text-white">
                K
            </div>

            <div>
                <h1 class="text-2xl font-bold text-slate-900">Krisna Net</h1>
                <p class="text-sm text-slate-500">Admin Panel</p>
            </div>
        </div>

        <nav class="px-4 pb-6">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="block rounded-2xl px-5 py-4 text-lg font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-700 hover:bg-slate-50' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="#" class="block rounded-2xl px-5 py-4 text-lg font-semibold text-slate-700 hover:bg-slate-50">
                        Data Pelanggan
                    </a>
                </li>
                <li>
                    <a href="#" class="block rounded-2xl px-5 py-4 text-lg font-semibold text-slate-700 hover:bg-slate-50">
                        Paket Internet
                    </a>
                </li>
                <li>
                    <a href="#" class="block rounded-2xl px-5 py-4 text-lg font-semibold text-slate-700 hover:bg-slate-50">
                        Manajemen Pembayaran
                    </a>
                </li>
                <li>
                    <a href="#" class="block rounded-2xl px-5 py-4 text-lg font-semibold text-slate-700 hover:bg-slate-50">
                        Riwayat Tagihan
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="min-w-0 flex-1">
        <header class="border-b border-slate-200 bg-white">
            <div class="flex items-center justify-between px-6 py-5 lg:px-8">
                <div class="text-2xl text-slate-500">☰</div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xl font-semibold text-slate-900">Admin User</p>
                        <p class="text-sm text-slate-500">Administrator</p>
                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500 text-lg font-bold text-white">
                        A
                    </div>
                </div>
            </div>
        </header>

        <main class="px-6 py-8 lg:px-8">
            @yield('content')
        </main>
    </div>
</div>
@endsection