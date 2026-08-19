@props([
    'url',
    'phone' => null,
])

@php
    $ariaLabel = filled($phone)
        ? __('whatsapp.float.aria_with_phone', ['phone' => $phone])
        : __('whatsapp.float.aria');
@endphp

{{-- Stays put while scrolling: fixed, and lifted on small screens so it clears
     the tour page's sticky "Book now" bar. --}}
<a
    href="{{ $url }}"
    target="_blank"
    rel="noopener noreferrer"
    class="group fixed bottom-24 right-5 z-30 flex h-14 w-14 items-center justify-center rounded-full transition duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2 motion-safe:hover:scale-105 lg:bottom-6 lg:right-6 lg:h-16 lg:w-16"
    aria-label="{{ $ariaLabel }}"
    title="{{ $ariaLabel }}"
>
    {{-- Two staggered rings behind the opaque badge: "ping ping", then a pause. --}}
    <span class="animate-whatsapp-ping pointer-events-none absolute inset-0 rounded-full bg-[#25D366]" aria-hidden="true"></span>
    <span class="animate-whatsapp-ping-delayed pointer-events-none absolute inset-0 rounded-full bg-[#25D366]" aria-hidden="true"></span>

    <span
        class="pointer-events-none absolute right-full top-1/2 mr-3 hidden -translate-y-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white opacity-0 shadow-lg transition duration-150 group-hover:opacity-100 lg:block"
    >
        {{ __('whatsapp.float.tooltip') }}
    </span>

    <span class="relative flex h-full w-full items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg ring-1 ring-black/5 transition duration-200 group-hover:shadow-xl">
        <x-icon name="whatsapp" size="xl" />
    </span>
</a>
