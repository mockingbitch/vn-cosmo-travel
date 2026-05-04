@props([
    'variant' => 'primary', // primary|secondary|ghost
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400/60 focus-visible:ring-offset-2';

    $variants = [
        'primary' => 'bg-slate-900 text-white hover:bg-slate-800 shadow-sm',
        'secondary' => 'bg-white text-slate-900 border border-slate-200 hover:bg-slate-50',
        'ghost' => 'bg-transparent text-slate-900 hover:bg-slate-100/70',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($attributes->get('class') ?? '');
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

