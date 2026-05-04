@props([
    'variant' => 'primary', // primary|secondary|danger|ghost
    'size' => 'md', // sm|md
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-2xl font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400/60 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50';
    $sizes = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2 text-sm',
    ];
    $variants = [
        'primary' => 'bg-slate-900 text-white shadow-sm hover:bg-slate-800',
        'secondary' => 'bg-white text-slate-900 border border-slate-200 shadow-sm hover:bg-slate-50',
        'danger' => 'bg-rose-600 text-white shadow-sm hover:bg-rose-700',
        'ghost' => 'bg-transparent text-slate-700 hover:bg-slate-100',
    ];

    $classes = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
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

