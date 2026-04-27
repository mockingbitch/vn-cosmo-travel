@props(['mainNav' => null])

@php
    $nav = is_array($mainNav) ? $mainNav : ['primary' => [], 'cruise' => [], 'dailyMega' => []];
    $items = $nav['primary'] ?? [];
    $cruise = $nav['cruise'] ?? [];
    $dm = $nav['dailyMega'] ?? [];
    $row1 = $dm['rows'][0] ?? [];
    $row2 = $dm['rows'][1] ?? [];
@endphp

<nav class="w-full" aria-label="{{ __('Main navigation') }}">
    <div class="space-y-1.5 text-sm font-medium text-slate-800">
        @foreach($items as $entry)
            @if(($entry['type'] ?? '') === 'link' && $entry['href'] !== '#')
                <a
                    href="{{ $entry['href'] }}"
                    @click="closeAll()"
                    class="block rounded-xl px-3.5 py-2.5 transition @if($entry['active'] ?? false) bg-slate-100 @else hover:bg-slate-50/95 @endif"
                >{{ $entry['label'] ?? '' }}</a>
            @elseif(($entry['type'] ?? '') === 'mega' && ($entry['panel'] ?? '') === 'daily')
                <div class="overflow-hidden rounded-xl border border-slate-200/90 bg-white">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between px-3.5 py-2.5 text-left font-semibold"
                        :class="mDaily ? 'text-slate-900' : 'text-slate-800'"
                        @click="mDaily = !mDaily"
                        :aria-expanded="mDaily.toString()"
                    >
                        {{ $entry['label'] ?? '' }}
                        <span class="text-slate-400" x-text="mDaily ? '−' : '+'"></span>
                    </button>
                    <div x-cloak x-show="mDaily" x-transition class="border-t border-slate-100/90 px-3.5 py-3">
                        <div class="max-h-[60vh] overflow-y-auto [scrollbar-gutter:stable]">
                            <x-site.mega-daily-lists
                                :row1="$row1"
                                :row2="$row2"
                                :featured="null"
                                :compact="true"
                            />
                        </div>
                        <a
                            href="{{ route('home') }}#destinations"
                            @click="closeAll()"
                            class="mt-3 block border-t border-slate-200/80 py-2.5 text-sm font-semibold text-emerald-800/90"
                        >{{ __('nav.see_all_destinations') }} →</a>
                    </div>
                </div>
            @elseif(($entry['type'] ?? '') === 'dropdown' && ($entry['panel'] ?? '') === 'cruise')
                <div class="overflow-hidden rounded-xl border border-slate-200/90 bg-white">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between px-3.5 py-2.5 text-left font-semibold"
                        :class="mCruise ? 'text-slate-900' : 'text-slate-800'"
                        @click="mCruise = !mCruise"
                        :aria-expanded="mCruise.toString()"
                    >
                        {{ $entry['label'] ?? '' }}
                        <span class="text-slate-400" x-text="mCruise ? '−' : '+'"></span>
                    </button>
                    <div x-cloak x-show="mCruise" x-transition class="space-y-0.5 border-t border-slate-100/90 px-1.5 py-1.5">
                        @foreach($cruise as $c)
                            <a
                                href="{{ $c['href'] }}"
                                @click="closeAll()"
                                class="block rounded-lg px-2.5 py-1.5 text-slate-700 transition hover:bg-slate-50"
                            >{{ $c['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</nav>
