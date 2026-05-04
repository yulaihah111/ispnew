@extends('layouts.app')

@php
    $title = 'Sign In - Krisna Net';
@endphp

@section('body')
<div class="flex min-h-screen bg-jobie-bg lg:bg-white font-sans">
    <!-- Panel Kiri: Artwork & Brand Message (Hanya Muncul di Desktop L & XL) -->
    <div class="hidden lg:flex lg:w-[45%] xl:w-1/2 relative bg-jobie-primary items-center justify-center overflow-hidden">
        
        <!-- Ornamen Latar Belakang (Blob & Shape Halus) -->
        <div class="absolute inset-0 z-0">
            <!-- Background Shape Abstract (Ombak Transparan) -->
            <svg class="absolute bottom-0 left-0 w-full opacity-10" viewBox="0 0 1440 320" preserveAspectRatio="none">
               <path fill="#ffffff" fill-opacity="1" d="M0,288L48,272C96,256,192,224,288,197.3C384,171,480,149,576,165.3C672,181,768,235,864,250.7C960,267,1056,245,1152,213.3C1248,181,1344,139,1392,117.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl"></div>

        <!-- Konten Panel Kiri (Text & Logo) -->
        <div class="relative z-10 p-12 lg:p-20 text-center text-white max-w-xl">
            <div class="flex h-[100px] w-[100px] mx-auto mb-10 items-center justify-center rounded-[2rem] bg-white text-jobie-primary shadow-2xl text-[54px] font-black transform -rotate-12 transition-transform duration-500 hover:rotate-0 hover:scale-105 cursor-pointer border-4 border-indigo-200/30">
                K
            </div>
            <h1 class="text-[40px] font-extrabold tracking-tight mb-6 leading-[1.15]">Sistem Krina Net</h1>
            <p class="text-[17px] text-indigo-100/80 font-medium leading-relaxed">Kelola pelanggan lokal tanpa hambatan. Kendalikan jaringan, konfigurasi tagihan, dan pacu performa operasional internet Anda secara presisi.</p>
        </div>
    </div>

    <!-- Panel Kanan: Login Form Area (Responsive) -->
    <div class="w-full lg:w-[55%] xl:w-1/2 flex items-center justify-center p-6 sm:p-12 md:p-16 relative">
        <div class="w-full max-w-[440px]">
            
            <!-- Logo Muncul di Mobile karena Panel Kiri Hilang -->
            <div class="mb-12 lg:hidden text-center flex flex-col items-center">
                 <div class="flex h-16 w-16 mb-4 items-center justify-center rounded-2xl bg-jobie-primary text-white shadow-lg shadow-jobie-primary/30 text-3xl font-black">K</div>
                 <h2 class="text-2xl font-extrabold text-slate-800">Krisna Net</h2>
            </div>

            <!-- Teks Sapaan Utama -->
            <div class="mb-10">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-3">Selamat Datang di Krisna Net 👋</h2>
                <p class="text-slate-500 font-medium text-[15px]">Silakan masukkan email & password untuk masuk.</p>
            </div>

            <!-- Pesan Error -->
            @if ($errors->any())
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-4 text-[14px] text-red-600 flex items-start gap-4 shadow-sm animate-pulse">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-500 text-lg"></i>
                    <span class="font-semibold">{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.attempt') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-[14px] font-bold text-slate-700 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-jobie-primary text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-[18px] pl-12 pr-5 text-slate-900 placeholder-slate-400 focus:bg-white focus:border-jobie-primary focus:ring-[4px] focus:ring-jobie-primary/10 transition-all outline-none text-[15px] font-semibold"
                            placeholder="namaanda@email.com"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-[14px] font-bold text-slate-700">
                            Kata Sandi
                        </label>
                        <a href="#" class="text-[13px] font-bold text-jobie-primary hover:text-[#1e3a8a] transition-colors">Lupa Akses?</a>
                    </div>
                    
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-jobie-primary text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50/50 py-[18px] pl-12 pr-5 text-slate-900 placeholder-slate-400 focus:bg-white focus:border-jobie-primary focus:ring-[4px] focus:ring-jobie-primary/10 transition-all outline-none text-[15px] font-semibold tracking-wider"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2 pb-2">
                    <input type="checkbox" id="remember" name="remember" value="1" class="h-5 w-5 rounded-md border-slate-300 text-jobie-primary bg-slate-50 focus:ring-jobie-primary cursor-pointer transition-colors">
                    <label for="remember" class="text-[14px] font-semibold text-slate-600 cursor-pointer select-none">Biarkan saya tetap masuk</label>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-jobie-primary py-4 text-[16px] font-bold text-white shadow-xl shadow-jobie-primary/30 transition-all duration-300 hover:bg-[#1e40af] hover:-translate-y-1 hover:shadow-jobie-primary/40 flex items-center justify-center gap-2 group"
                >
                    Log In WebPanel <i class="fa-solid fa-arrow-right-to-bracket ml-1 group-hover:translate-x-1 transition-transform duration-300"></i>
                </button>
            </form>

            <!-- Test Credentials Helper Container (Menghilang saat production bisa ditambahkan kondisi nanti) -->
            <div class="mt-14 w-full pt-8 border-t border-slate-200/80">
                <div class="rounded-2xl bg-indigo-50/50 p-5 text-sm text-slate-600 border border-indigo-100/50">
                    <p class="font-extrabold text-slate-800 mb-3 flex items-center gap-2">
                         <i class="fa-solid fa-flask text-jobie-primary"></i> Dummy Accounts:
                    </p>
                    <div class="grid grid-cols-1 gap-3">
                         <div class="flex justify-between items-center bg-white p-3 px-4 rounded-xl shadow-sm border border-slate-100">
                              <span class="font-bold text-slate-700 flex items-center gap-2"><div class="w-2 h-2 rounded bg-purple-500"></div> Admin</span>
                              <span class="text-slate-500 font-mono text-[12px] font-semibold tracking-tight">admin@krisnanet.test / password</span>
                         </div>
                         <div class="flex justify-between items-center bg-white p-3 px-4 rounded-xl shadow-sm border border-slate-100">
                              <span class="font-bold text-slate-700 flex items-center gap-2"><div class="w-2 h-2 rounded bg-emerald-500"></div> Customer</span>
                              <span class="text-slate-500 font-mono text-[12px] font-semibold tracking-tight">ahmad@krisnanet.test / password</span>
                         </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection