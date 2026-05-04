@props([
    'href' => null,
    'icon' => 'pencil', // pencil | trash | eye (aliases resolved by IconRegistry)
    'variant' => 'default', // default | danger
    'title' => null,
])

@php
    $base = 'inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-xl border bg-white shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-1';
    $variants = [
        'default' => 'border-slate-200 text-slate-600 hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700 focus:ring-indigo-300/60',
        'danger' => 'border-slate-200 text-slate-600 hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700 focus:ring-rose-300/60',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['default']);
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($title) title="{{ $title }}" aria-label="{{ $title }}" @endif
    >
        <x-icon :name="$icon" size="sm" />
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($title) title="{{ $title }}" aria-label="{{ $title }}" @endif
    >
        <x-icon :name="$icon" size="sm" />
    </button>
@endif
