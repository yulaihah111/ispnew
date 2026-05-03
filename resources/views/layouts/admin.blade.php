@extends('layouts.app')

@section('body')
<div class="min-h-screen bg-jobie-primary flex font-sans">
    
    <!-- Sidebar -->
    <aside class="w-full md:min-h-screen md:w-72 md:shrink-0 flex flex-col py-6">
        <div class="flex items-center px-8 mb-12">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-jobie-primary text-3xl font-black shadow-lg">
                K
            </div>
            <h1 class="ml-4 text-2xl font-bold text-white tracking-wide">Krisna Net</h1>
        </div>

        <nav class="pl-6 flex-1">
            <ul class="space-y-2">
                <li class="relative">
                    <a href="{{ route('admin.dashboard') }}"
                        class="block px-6 py-4 text-[16px] {{ request()->routeIs('admin.dashboard') ? 'menu-bridge' : 'text-white/70 hover:text-white transition-colors' }} flex items-center gap-4">
                        <i class="fa-solid fa-house w-6 text-center text-lg"></i>
                        Dashboard
                    </a>
                </li>

                <li class="relative">
                    <a href="{{ route('admin.customers.index') }}"
                       class="block px-6 py-4 text-[16px] {{ request()->routeIs('admin.customers.*') ? 'menu-bridge' : 'text-white/70 hover:text-white transition-colors' }} flex items-center gap-4">
                        <i class="fa-solid fa-users w-6 text-center text-lg"></i>
                        Data Pelanggan
                    </a>
                </li>

                <li class="relative">
                    <a href="{{ route('admin.packages.index') }}"
                    class="block px-6 py-4 text-[16px] {{ request()->routeIs('admin.packages.*') ? 'menu-bridge' : 'text-white/70 hover:text-white transition-colors' }} flex items-center gap-4">
                        <i class="fa-solid fa-wifi w-6 text-center text-lg"></i>
                        Paket Internet
                    </a>
                </li>

                <li class="relative">
                    <a href="{{ route('admin.invoices.index') }}"
                       class="block px-6 py-4 text-[16px] {{ request()->routeIs('admin.invoices.*') ? 'menu-bridge' : 'text-white/70 hover:text-white transition-colors' }} flex items-center gap-4">
                        <i class="fa-solid fa-file-invoice-dollar w-6 text-center text-lg"></i>
                        Manajemen Pembayaran
                    </a>
                </li>

                <li class="relative">
                    <a href="{{ route('admin.reminders.index') }}"
                       class="block px-6 py-4 text-[16px] {{ request()->routeIs('admin.reminders.*') ? 'menu-bridge' : 'text-white/70 hover:text-white transition-colors' }} flex items-center gap-4">
                        <i class="fa-brands fa-whatsapp w-6 text-center text-lg"></i>
                        Reminder WhatsApp
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="px-8 mt-auto mb-4">
             <div class="text-white/40 text-xs leading-relaxed">
                 Krisna Net Admin Dashboard<br>
                 © 2026 Sang Developer
             </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 my-2 mr-2 bg-jobie-bg rounded-l-[2.5rem] rounded-r-2xl overflow-hidden shadow-2xl flex flex-col min-w-0">
        
        <!-- Header -->
        <header class="bg-jobie-bg pt-8 pb-4 px-8 lg:px-12 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <i class="fa-solid fa-bars text-slate-400 text-2xl hover:text-jobie-primary cursor-pointer transition-colors"></i>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard WebPanel</h2>
            </div>
            
            <div class="flex items-center gap-8 text-slate-600">
                <!-- Search Bar -->
                <form action="{{ request()->url() }}" method="GET" class="hidden lg:flex items-center bg-white rounded-full px-6 py-3 shadow border border-slate-100/50 w-80">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari data pelanggan..." class="bg-transparent border-none outline-none text-[15px] w-full text-slate-700 placeholder-slate-400 font-medium">
                </form>

                <!-- Icons -->
                <div class="flex items-center gap-6">
                    <div class="relative cursor-pointer text-slate-400 hover:text-jobie-primary transition-colors group">
                        <i class="fa-solid fa-message text-xl"></i>
                        <span class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-jobie-primary text-[10px] font-bold text-white border-[3px] border-jobie-bg">3</span>
                        <!-- Dropdown Messages -->
                        <div class="absolute right-0 top-full mt-4 w-72 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden transform translate-y-2 group-hover:translate-y-0 text-left cursor-default">
                            <div class="p-4 border-b border-slate-100 font-bold text-slate-800">Pesan Masuk</div>
                            <div class="divide-y divide-slate-50">
                                <a href="#" class="block p-4 hover:bg-slate-50 transition-colors">
                                    <p class="text-sm font-semibold text-slate-800">Ahmad Wijaya</p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">Tolong cek koneksi internet saya...</p>
                                </a>
                                <a href="#" class="block p-4 hover:bg-slate-50 transition-colors">
                                    <p class="text-sm font-semibold text-slate-800">Siti Nurhaliza</p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">Bukti bayar tagihan bulan ini trmksh</p>
                                </a>
                                <a href="#" class="block p-4 hover:bg-slate-50 transition-colors">
                                    <p class="text-sm font-semibold text-slate-800">Budi Santoso</p>
                                    <p class="text-xs text-slate-500 mt-1 truncate">Pak, apakah bisa upgrade paket?</p>
                                </a>
                            </div>
                            <a href="#" class="block p-3 text-center text-sm font-semibold text-jobie-primary hover:bg-slate-50 bg-slate-50/50">Tampilkan Semua Pesan</a>
                        </div>
                    </div>
                    <div class="relative cursor-pointer text-slate-400 hover:text-jobie-primary transition-colors group">
                        <i class="fa-solid fa-bell text-xl"></i>
                        <span class="absolute -top-2 -right-2 flex h-5 w-5 items-center justify-center rounded-full bg-jobie-primary text-[10px] font-bold text-white border-[3px] border-jobie-bg">2</span>
                        <!-- Dropdown Notifications -->
                        <div class="absolute right-0 top-full mt-4 w-80 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden transform translate-y-2 group-hover:translate-y-0 text-left cursor-default">
                            <div class="p-4 border-b border-slate-100 font-bold text-slate-800">Notifikasi</div>
                            <div class="divide-y divide-slate-50">
                                <a href="#" class="flex gap-4 p-4 hover:bg-slate-50 transition-colors items-start">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Konfirmasi Pembayaran</p>
                                        <p class="text-xs text-slate-500 mt-1">Siti Nurhaliza baru saja mengirimkan bukti pembayaran.</p>
                                        <p class="text-[10px] text-slate-400 mt-1">10 menit yang lalu</p>
                                    </div>
                                </a>
                                <a href="#" class="flex gap-4 p-4 hover:bg-slate-50 transition-colors items-start">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">Pelanggan Baru</p>
                                        <p class="text-xs text-slate-500 mt-1">Budi Santoso telah teregistrasi di sistem.</p>
                                        <p class="text-[10px] text-slate-400 mt-1">1 jam yang lalu</p>
                                    </div>
                                </a>
                            </div>
                            <a href="#" class="block p-3 text-center text-sm font-semibold text-jobie-primary hover:bg-slate-50 bg-slate-50/50">Tampilkan Semua Notifikasi</a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="flex items-center gap-4 pl-2 border-l border-slate-200">
                    <div class="text-right hidden md:block">
                        <p class="text-[15px] font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <p class="text-xs text-slate-400 font-medium">Super Admin</p>
                    </div>
                    
                    <div class="relative group cursor-pointer inline-flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=2563eb&color=fff&rounded=true" alt="User Avatar" class="h-12 w-12 rounded-full shadow-sm object-cover border-2 border-white group-hover:scale-105 transition-transform">
                        
                        <!-- Dropdown Logout Form inside group hover -->
                        <div class="absolute right-0 top-full mt-2 w-48 rounded-2xl bg-white shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden transform translate-y-2 group-hover:translate-y-0 text-left">
                             <form action="{{ route('logout') }}" method="POST">
                                 @csrf
                                 <button type="submit" class="w-full text-left px-5 py-4 text-[15px] font-semibold text-red-500 hover:bg-red-50 transition-colors flex items-center gap-3">
                                     <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout Account
                                 </button>
                             </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Inner Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto px-8 lg:px-12 py-6">
            @yield('content')
        </main>
        
    </div>
</div>
@endsection