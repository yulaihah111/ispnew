@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur sticky top-0 z-10 shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-lg font-bold text-white shadow-md cursor-pointer hover:scale-105 transition-transform">
                    K
                </div>

                <div>
                    <h1 class="text-xl font-bold leading-tight text-slate-900">Krisna Net</h1>
                    <p class="text-xs text-slate-500">Portal Pelanggan</p>
                </div>
            </div>

            <nav class="flex items-center gap-6 text-sm font-semibold text-slate-900">
                <a href="{{ route('customer.dashboard') }}" class="transition-all duration-200 hover:text-blue-600 hover:-translate-y-0.5">Dashboard</a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-xl border border-red-200 bg-red-50 text-red-600 px-4 py-1.5 transition-all hover:bg-red-500 hover:text-white shadow-sm hover:-translate-y-0.5">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="px-5 py-7 lg:px-8">
        @yield('content')
    </main>
</div>
@endsection