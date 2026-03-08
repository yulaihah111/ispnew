@props([
    'status' => 'default',
])

@php
    $classes = match ($status) {
        'paid', 'active', 'lunas' => 'bg-green-100 text-green-700',
        'pending', 'pending_verification', 'menunggu' => 'bg-orange-100 text-orange-700',
        'unpaid', 'inactive', 'belum_bayar' => 'bg-red-100 text-red-700',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {$classes}"]) }}>
    {{ $slot }}
</span>