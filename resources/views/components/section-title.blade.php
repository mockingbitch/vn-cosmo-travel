@props([
    'title',
    'subtitle' => null,
    'align' => 'left', // left|center
])

@php
    $wrap = $align === 'center' ? 'text-center' : '';
@endphp

<div {{ $attributes->merge(['class' => $wrap]) }}>
    <div class="text-sm font-semibold tracking-wide text-slate-500">{{ __('ui.brand_name') }}</div>
    <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
        {{ $title }}
    </h2>
    @if($subtitle)
        <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">
            {{ $subtitle }}
        </p>
    @endif
</div>

