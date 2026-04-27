@props([ 'primary' => [], 'cruise' => [] ])

@php
    $cruiseItems = is_array($cruise) ? $cruise : [];
    $items = is_array($primary) ? $primary : [];
@endphp

<nav
    class="hidden w-full max-w-4xl shrink-0 items-center justify-center gap-0.5 text-sm font-medium text-slate-700 lg:flex lg:gap-1.5 min-[80rem]:text-[0.8rem]"
    role="menubar"
    aria-label="{{ __('Main navigation') }}"
>
    @foreach($items as $entry)
        @if(($entry['type'] ?? '') === 'link' && $entry['href'] !== '#')
            <a
                href="{{ $entry['href'] }}"
                @class([
                    'rounded-lg px-2.5 py-2 transition',
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
                    class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 transition"
                    :class="panel === 'daily' ? 'bg-slate-100/95 text-slate-900' : 'text-slate-800 hover:text-slate-900'"
                    @click="isDesktop ? (clearTimers(), (panel = panel === 'daily' ? null : 'daily')) : toggle('daily')"
                    :aria-expanded="(panel === 'daily').toString()"
                    aria-controls="mega-daily-trips"
                >
                    <span class="whitespace-nowrap">{{ $entry['label'] ?? '' }}</span>
                    <svg
                        class="h-3.5 w-3.5 shrink-0 text-slate-500 transition"
                        :class="panel==='daily' && 'rotate-180'"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @elseif(($entry['type'] ?? '') === 'dropdown' && ($entry['panel'] ?? '') === 'cruise')
            <div
                class="relative flex items-center"
                @mouseenter="onTriggerEnter('cruise')"
                @mouseleave="onTriggerLeave()"
            >
                <button
                    type="button"
                    class="inline-flex items-center gap-1 rounded-lg px-2.5 py-2 transition"
                    :class="panel === 'cruise' ? 'bg-slate-100/95 text-slate-900' : ($entry['active'] ?? false ? 'bg-slate-100/95' : 'text-slate-800 hover:text-slate-900')"
                    @click="isDesktop ? (clearTimers(), (panel = panel === 'cruise' ? null : 'cruise')) : toggle('cruise')"
                    :aria-expanded="(panel === 'cruise').toString()"
                    aria-haspopup="true"
                    aria-controls="dropdown-cruise"
                >
                    <span class="whitespace-nowrap">{{ $entry['label'] ?? '' }}</span>
                    <svg
                        class="h-3.5 w-3.5 shrink-0 text-slate-500 transition"
                        :class="panel==='cruise' && 'rotate-180'"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <div
                    x-cloak
                    x-show="panel==='cruise' && isDesktop"
                    @mouseenter="onPanelEnter()"
                    @mouseleave="onPanelLeave()"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-0.5"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    id="dropdown-cruise"
                    class="max-lg:hidden absolute left-0 top-full z-50 mt-1.5 min-w-[19rem] rounded-2xl border border-slate-200/80 bg-white py-2.5 text-sm shadow-2xl"
                    role="menu"
                >
                    @foreach($cruiseItems as $c)
                        <a
                            href="{{ $c['href'] }}"
                            class="block px-4 py-2.5 text-slate-800 transition hover:bg-slate-50/95 hover:text-emerald-800"
                            role="menuitem"
                        >{{ $c['label'] }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</nav>
