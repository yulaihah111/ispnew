@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-slate-100">
    <header class="border-b border-slate-200 bg-white">
        <div class="flex items-center justify-between px-5 py-4 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500 text-lg font-bold text-white">
                    K
                </div>

                <div>
                    <h1 class="text-xl font-bold leading-tight text-slate-900">Krisna Net</h1>
                    <p class="text-xs text-slate-500">Portal Pelanggan</p>
                </div>
            </div>

            <nav class="flex items-center gap-6 text-sm font-semibold text-slate-900">
                <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-600">Dashboard</a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="hover:text-blue-600">
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