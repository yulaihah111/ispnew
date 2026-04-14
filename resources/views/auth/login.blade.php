@extends('layouts.app')

@php
    $title = 'Login';
@endphp

@section('body')
<div class="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-10">
    <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-8 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-500 text-2xl font-bold text-white">
                K
            </div>
            <h1 class="mt-4 text-3xl font-bold text-slate-900">Krisna Net Billing</h1>
            <p class="mt-2 text-sm text-slate-500">Silakan login untuk melanjutkan</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                    Email
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Masukkan email"
                    required
                    autofocus
                >
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">
                <span>Ingat saya</span>
            </label>

            <button
                type="submit"
                class="w-full rounded-2xl bg-blue-500 px-4 py-3 text-base font-semibold text-white transition hover:bg-blue-600"
            >
                Login
            </button>
        </form>

        <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
            <p class="font-semibold text-slate-800">Akun dummy testing:</p>
            <p class="mt-2">Admin: admin@krisnanet.test / password</p>
            <p>Customer: ahmad@krisnanet.test / password</p>
        </div>
    </div>
</div>
@endsection