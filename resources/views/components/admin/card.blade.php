@props([
    'title' => null,
    'subtitle' => null,
    // Card titles are headings: h2 by default, h1 for a page that is one card.
    'heading' => 'h2',
])

@php
    $headingTag = in_array($heading, ['h1', 'h2', 'h3'], true) ? $heading : 'h2';
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm']) }}>
    @if($title)
        <div class="flex items-start justify-between gap-4">
            <div>
                <{{ $headingTag }} class="text-sm font-semibold text-slate-900">{{ $title }}</{{ $headingTag }}>
                @if($subtitle)
                    <div class="mt-1 text-sm text-slate-600">{{ $subtitle }}</div>
                @endif
            </div>
            @if(trim((string) ($actions ?? '')) !== '')
                <div class="shrink-0">
                    {{ $actions }}
                </div>
            @endif
        </div>
        <div class="mt-4">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>

