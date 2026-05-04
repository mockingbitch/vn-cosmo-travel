@props(['mainNav' => null])

@php
    $nav = is_array($mainNav) ? $mainNav : ['primary' => [], 'dailyMega' => [], 'dropdownPanels' => []];
    $items = $nav['primary'] ?? [];
    $dropdownPanels = is_array($nav['dropdownPanels'] ?? null) ? $nav['dropdownPanels'] : [];
    $dm = $nav['dailyMega'] ?? [];
    $row1 = $dm['rows'][0] ?? [];
    $row2 = $dm['rows'][1] ?? [];
@endphp

<nav class="w-full" aria-label="{{ __('Main navigation') }}">
    <div class="space-y-1.5 text-sm font-medium text-slate-800">
        @foreach($items as $entry)
            @if(($entry['type'] ?? '') === 'link' && ($entry['href'] ?? '#') !== '#')
                <a
                    href="{{ $entry['href'] }}"
                    @click="closeAll()"
                    class="block rounded-xl px-3.5 py-2.5 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2 @if($entry['active'] ?? false) bg-slate-100 @else hover:bg-slate-50/95 @endif"
                >{{ $entry['label'] ?? '' }}</a>
            @elseif(($entry['type'] ?? '') === 'mega' && ($entry['panel'] ?? '') === 'daily')
                <div class="overflow-hidden rounded-xl border border-slate-200/90 bg-white">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between px-3.5 py-2.5 text-left font-semibold focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-slate-400"
                        :class="mobileSection === 'mega-daily' ? 'text-slate-900' : 'text-slate-800'"
                        @click="toggleMobileSection('mega-daily')"
                        :aria-expanded="(mobileSection === 'mega-daily').toString()"
                        aria-controls="mobile-panel-mega-daily"
                    >
                        {{ $entry['label'] ?? '' }}
                        <span class="text-slate-400" aria-hidden="true" x-text="mobileSection === 'mega-daily' ? '−' : '+'"></span>
                    </button>
                    <div id="mobile-panel-mega-daily" x-cloak x-show="mobileSection === 'mega-daily'" x-transition class="border-t border-slate-100/90 px-3.5 py-3">
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
            @elseif(($entry['type'] ?? '') === 'dropdown')
                @php
                    $panelKey = (string) ($entry['panel'] ?? '');
                    $panelItems = $panelKey !== '' ? ($dropdownPanels[$panelKey] ?? []) : [];
                    $mobilePanelDomId = 'mobile-panel-'.preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace('_', '-', $panelKey));
                @endphp
                @continue($panelKey === '' || $panelItems === [])
                <div class="overflow-hidden rounded-xl border border-slate-200/90 bg-white">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between px-3.5 py-2.5 text-left font-semibold focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-slate-400"
                        :class="mobileSection === @js($panelKey) ? 'text-slate-900' : 'text-slate-800'"
                        @click="toggleMobileSection(@js($panelKey))"
                        :aria-expanded="(mobileSection === @js($panelKey)).toString()"
                        aria-controls="{{ $mobilePanelDomId }}"
                    >
                        {{ $entry['label'] ?? '' }}
                        <span class="text-slate-400" aria-hidden="true" x-text="mobileSection === @js($panelKey) ? '−' : '+'"></span>
                    </button>
                    <div id="{{ $mobilePanelDomId }}" x-cloak x-show="mobileSection === @js($panelKey)" x-transition class="space-y-0.5 border-t border-slate-100/90 px-1.5 py-1.5">
                        @foreach($panelItems as $item)
                            <a
                                href="{{ $item['href'] }}"
                                @click="closeAll()"
                                class="block rounded-lg px-2.5 py-1.5 text-slate-700 transition hover:bg-slate-50"
                            >{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</nav>
