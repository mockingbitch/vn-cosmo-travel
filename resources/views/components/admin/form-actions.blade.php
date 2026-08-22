@props([
    // Primary submit
    'submitLabel' => null,
    'submitIcon' => 'save',
    // Secondary "back out" link; omit to render no cancel
    'cancelUrl' => null,
    'cancelLabel' => null,
    // Long forms keep the bar in reach while scrolling; it sits in normal flow
    // on short pages, so this is safe to leave on.
    'sticky' => true,
])

@php
    $submitLabel = $submitLabel ?: __('save');
    $cancelLabel = $cancelLabel ?: __('cancel');
@endphp

{{-- One placement for every admin form: primary first, then cancel, aligned with
     the start of the fields above it (the form is wide, so right-aligned buttons
     would sit far from what the user was just editing). Extra actions go in the
     slot and are pushed to the trailing edge. --}}
<div @class([
    'flex flex-wrap items-center gap-3',
    'sticky bottom-4 z-20 mt-8 rounded-2xl border border-slate-200 bg-white/95 px-4 py-3 shadow-lg ring-1 ring-slate-900/5 backdrop-blur' => $sticky,
    'mt-8 border-t border-slate-100 pt-6' => ! $sticky,
])>
    <button
        type="submit"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2"
    >
        <x-icon :name="$submitIcon" size="sm" />
        {{ $submitLabel }}
    </button>

    @if($cancelUrl)
        <a
            href="{{ $cancelUrl }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2"
        >
            <x-icon name="arrow-left" size="sm" />
            {{ $cancelLabel }}
        </a>
    @endif

    @if(trim((string) $slot) !== '')
        <div class="ms-auto flex flex-wrap items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
