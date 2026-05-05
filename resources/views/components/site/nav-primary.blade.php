@props([
    'primary' => [],
    'dropdownPanels' => [],
])

@php
    /** @var array<string, list<array{label: string, href: string}>> $dropdownPanels */
    $dropdownPanels = is_array($dropdownPanels) ? $dropdownPanels : [];
    $items = is_array($primary) ? $primary : [];
@endphp

<nav
    class="hidden w-full max-w-4xl shrink-0 items-center justify-center gap-0.5 text-sm font-medium text-slate-700 lg:flex lg:gap-1.5 min-[80rem]:text-[0.8rem]"
    role="menubar"
    aria-label="{{ __('nav.header.main_navigation') }}"
>
    @foreach($items as $entry)
        @if(($entry['type'] ?? '') === 'link' && ($entry['href'] ?? '#') !== '#')
            <a
                href="{{ $entry['href'] }}"
                @class([
                    'rounded-lg px-2.5 py-2 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2',
                    'bg-slate-100/95 text-slate-900' => $entry['active'] ?? false,
                    'text-slate-800 hover:text-slate-900' => !($entry['active'] ?? false),
                ])
            >{{ $entry['label'] ?? '' }}</a>
        @elseif(($entry['type'] ?? '') === 'mega' && ($entry['panel'] ?? '') === 'daily')
            <div
                class="relative flex items-center"
                @mouseenter="onTriggerEnter('daily')"
                @mouseleave="onTriggerLeave()"
            >
                <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2"
                    :class="panel === 'daily' ? 'bg-slate-100/95 text-slate-900' : 'text-slate-800 hover:text-slate-900'"
                    @click="isDesktop ? (clearTimers(), (panel = panel === 'daily' ? null : 'daily')) : toggle('daily')"
                    :aria-expanded="(panel === 'daily').toString()"
                    aria-haspopup="true"
                    aria-controls="mega-daily-trips"
                >
                    <span class="whitespace-nowrap">{{ $entry['label'] ?? '' }}</span>
                    <span
                        class="inline-flex shrink-0 text-slate-500 transition"
                        :class="panel==='daily' && 'rotate-180'"
                    >
                        <x-icon name="chevron-down" size="sm" class="!h-3.5 !w-3.5" />
                    </span>
                </button>
            </div>
        @elseif(($entry['type'] ?? '') === 'dropdown')
            @php
                $panelKey = (string) ($entry['panel'] ?? '');
                $panelItems = $panelKey !== '' ? ($dropdownPanels[$panelKey] ?? []) : [];
                $panelDomId = $panelKey !== '' ? 'dropdown-panel-'.preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace('_', '-', $panelKey)) : 'dropdown-panel';
            @endphp
            @continue($panelKey === '' || $panelItems === [])
            <div
                class="relative flex items-center"
                @mouseenter="onTriggerEnter(@js($panelKey))"
                @mouseleave="onTriggerLeave()"
            >
                <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2"
                    :class="panel === @js($panelKey) ? 'bg-slate-100/95 text-slate-900' : ($entry['active'] ?? false ? 'bg-slate-100/95' : 'text-slate-800 hover:text-slate-900')"
                    @click="isDesktop ? (clearTimers(), (panel = panel === @js($panelKey) ? null : @js($panelKey))) : toggle(@js($panelKey))"
                    :aria-expanded="(panel === @js($panelKey)).toString()"
                    aria-haspopup="true"
                    aria-controls="{{ $panelDomId }}"
                >
                    <span class="whitespace-nowrap">{{ $entry['label'] ?? '' }}</span>
                    <span
                        class="inline-flex shrink-0 text-slate-500 transition"
                        :class="panel===@js($panelKey) && 'rotate-180'"
                    >
                        <x-icon name="chevron-down" size="sm" class="!h-3.5 !w-3.5" />
                    </span>
                </button>
                <div
                    x-cloak
                    x-show="panel===@js($panelKey) && isDesktop"
                    @mouseenter="onPanelEnter()"
                    @mouseleave="onPanelLeave()"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-0.5"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    id="{{ $panelDomId }}"
                    class="max-lg:hidden absolute left-0 top-full z-50 mt-1.5 min-w-[19rem] rounded-2xl border border-slate-200/80 bg-white py-2.5 text-sm shadow-2xl"
                    role="menu"
                >
                    @foreach($panelItems as $item)
                        <a
                            href="{{ $item['href'] }}"
                            class="block px-4 py-2.5 text-slate-800 transition hover:bg-slate-50/95 hover:text-emerald-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-slate-400"
                            role="menuitem"
                        >{{ $item['label'] }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</nav>
