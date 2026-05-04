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
                 © 2026 Sang Developer Yulaihah
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
                

                <!-- Icons -->
                <div class="flex items-center gap-6">
                    
                    
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