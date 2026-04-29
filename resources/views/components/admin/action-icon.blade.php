@props([
    'href' => null,
    'icon' => 'pencil', // pencil | trash | eye
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

    $icons = [
        'pencil' => '<path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />',
        'trash' => '<path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21q.342.052.682.107m-.682-.107L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562q.34-.055.682-.107m0 0a48.111 48.111 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />',
        'eye' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
    ];
    $iconSvg = $icons[$icon] ?? $icons['pencil'];
@endphp

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($title) title="{{ $title }}" aria-label="{{ $title }}" @endif
    >
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            {!! $iconSvg !!}
        </svg>
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge(['class' => $classes]) }}
        @if($title) title="{{ $title }}" aria-label="{{ $title }}" @endif
    >
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            {!! $iconSvg !!}
        </svg>
    </button>
@endif
