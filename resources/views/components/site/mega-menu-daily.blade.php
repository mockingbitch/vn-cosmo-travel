@props(['daily' => null])

@php
    $daily = $daily ?? ['rows' => [], 'featured' => null];
    $row1 = $daily['rows'][0] ?? [];
    $row2 = $daily['rows'][1] ?? [];
    $featured = $daily['featured'] ?? null;
@endphp

<div
    x-cloak
    x-show="panel === 'daily' && isDesktop"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-1"
    @mouseenter="onPanelEnter()"
    @mouseleave="onPanelLeave()"
    id="mega-daily-trips"
    class="max-lg:hidden"
    role="region"
    :aria-hidden="!isDesktop || panel !== 'daily' ? 'true' : 'false'"
>
    <div
        class="nav-mega-daily border-t border-slate-200/60 bg-slate-50/50 px-3 py-2 sm:px-4"
    >
        <div
            class="mx-auto w-full max-w-[min(90rem,95vw)] rounded-2xl border border-slate-200/60 bg-white px-4 py-8 shadow-2xl shadow-slate-900/10 sm:px-6 sm:py-9 lg:px-10"
        >
            <x-site.mega-daily-lists :row1="$row1" :row2="$row2" :featured="$featured" />

            <a
                href="{{ route('home') }}#destinations"
                class="mt-8 inline-flex text-sm font-semibold text-slate-700 transition hover:text-emerald-700/90"
            >{{ __('nav.see_all_destinations') }} →</a>
        </div>
    </div>
</div>
