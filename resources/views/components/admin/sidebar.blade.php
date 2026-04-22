@php
    $sections = [
        [
            'title' => __('General'),
            'items' => [
                ['label' => __('Dashboard'), 'route' => 'admin.dashboard', 'icon' => 'home'],
            ],
        ],
        [
            'title' => __('Content'),
            'items' => [
                ['label' => __('Hero Banners'), 'route' => 'admin.banners.edit', 'icon' => 'photo'],
                ['label' => __('Blog'), 'route' => 'admin.posts.index', 'icon' => 'document'],
                ['label' => __('Media'), 'route' => 'admin.media.index', 'icon' => 'folder'],
            ],
        ],
        [
            'title' => __('Settings'),
            'items' => [
                ['label' => __('Settings'), 'route' => 'admin.settings.edit', 'icon' => 'cog'],
            ],
        ],
    ];

    $icon = function (string $name): string {
        return match ($name) {
            'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75V19.5a2.25 2.25 0 0 0 2.25 2.25h3.75v-6a2.25 2.25 0 0 1 2.25-2.25h.75a2.25 2.25 0 0 1 2.25 2.25v6h3.75A2.25 2.25 0 0 0 19.5 19.5V9.75"/>',
            'cog' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 1 12.75-5.303M19.5 12a7.5 7.5 0 0 1-12.75 5.303M12 9.75A2.25 2.25 0 1 0 12 14.25 2.25 2.25 0 0 0 12 9.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25v1.5m0 16.5v1.5M3.75 12h-1.5m18 0h1.5M5.47 5.47 4.41 4.41m15.18 15.18 1.06 1.06M18.53 5.47l1.06-1.06M4.41 19.59l1.06-1.06"/>',
            'photo' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.159 2.159M3 18.75h18A2.25 2.25 0 0 0 23.25 16.5V6A2.25 2.25 0 0 0 21 3.75H3A2.25 2.25 0 0 0 .75 6v10.5A2.25 2.25 0 0 0 3 18.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h.008v.008H7.5V8.25Z"/>',
            'document' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m0 11.25h7.5m-7.5 3h7.5m-7.5 3h4.5M6.75 2.25H10.5A2.25 2.25 0 0 1 12.75 4.5v2.25A2.25 2.25 0 0 0 15 9h2.25A2.25 2.25 0 0 1 19.5 11.25v9A2.25 2.25 0 0 1 17.25 22.5h-9A2.25 2.25 0 0 1 6 20.25V4.5A2.25 2.25 0 0 1 8.25 2.25Z"/>',
            'folder' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V6A2.25 2.25 0 0 1 4.5 3.75h4.127c.597 0 1.17.237 1.592.659l.622.622c.422.422.995.659 1.591.659H19.5A2.25 2.25 0 0 1 21.75 7.5v5.25m-19.5 0h19.5m-19.5 0v6.75A2.25 2.25 0 0 0 4.5 21.75h15A2.25 2.25 0 0 0 21.75 19.5v-6.75"/>',
            default => '',
        };
    };
@endphp

<!-- Mobile overlay -->
<div
    x-show="sidebarOpen"
    x-transition.opacity
    class="fixed inset-0 z-40 bg-slate-900/40 lg:hidden"
    @click="sidebarOpen = false"
></div>

<aside
    class="fixed inset-y-0 left-0 z-50 flex h-full flex-col border-r border-slate-200/70 bg-white/80 shadow-xl backdrop-blur supports-[backdrop-filter]:bg-white/70 lg:sticky lg:z-auto lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="transition-transform duration-200 ease-in-out"
    :style="sidebarCollapsed ? 'width: 5rem;' : 'width: 18rem;'"
>
    <div class="flex items-center justify-between gap-3 px-4 py-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl bg-slate-900 text-sm font-semibold text-white shadow-sm">VC</span>
            <span class="min-w-0" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                <span class="block truncate text-sm font-semibold text-slate-900">{{ config('app.name') }}</span>
                <span class="block truncate text-xs font-medium text-slate-500">{{ __('Admin') }}</span>
            </span>
        </a>

        <div class="flex items-center gap-2">
            <button
                type="button"
                class="hidden items-center justify-center rounded-xl border border-slate-200 bg-white/70 p-2 text-slate-700 shadow-sm transition-all duration-200 hover:bg-slate-50 hover:shadow md:inline-flex lg:inline-flex"
                @click="
                    sidebarCollapsed = !sidebarCollapsed;
                    try { localStorage.setItem('admin.sidebarCollapsed', sidebarCollapsed ? '1' : '0'); } catch (e) {}
                "
                :aria-pressed="sidebarCollapsed.toString()"
                aria-label="Collapse sidebar"
            >
                <svg class="h-5 w-5 transition-transform duration-200" :class="sidebarCollapsed ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 p-2 text-slate-700 shadow-sm transition hover:bg-slate-50 lg:hidden"
                @click="sidebarOpen = false"
                aria-label="Close sidebar"
            >
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="px-4 pb-3" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                </svg>
            </div>
            <input
                type="search"
                placeholder="{{ __('Search') }}"
                class="w-full rounded-2xl border border-slate-200 bg-white/70 px-10 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-200/60"
            />
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 pb-4 [scrollbar-width:thin] [scrollbar-color:rgb(203,213,225)_transparent]">
        @foreach($sections as $section)
            <div class="mt-4 first:mt-0">
                <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400" x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms>
                    {{ $section['title'] }}
                </div>

                <div class="grid gap-1">
                    @foreach($section['items'] as $item)
                        @php
                            $isDisabled = empty($item['route']);
                            $active = !$isDisabled && request()->routeIs($item['route'].'*');
                        @endphp

                        <a
                            href="{{ $isDisabled ? '#' : route($item['route']) }}"
                            class="group relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 ease-in-out
                                {{ $active ? 'bg-indigo-600/10 text-indigo-700' : ($isDisabled ? 'cursor-not-allowed opacity-50' : 'text-slate-700 hover:bg-slate-100/70 hover:shadow-sm hover:scale-[1.01]') }}"
                            {{ $isDisabled ? 'aria-disabled=true' : '' }}
                        >
                            <span class="absolute left-0 top-1/2 h-6 w-[3px] -translate-y-1/2 rounded-r bg-indigo-600 transition-opacity {{ $active ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>

                            <svg
                                class="h-5 w-5 shrink-0 transition-colors duration-200
                                    {{ $active ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-700' }}"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                {!! $icon($item['icon']) !!}
                            </svg>

                            <span class="min-w-0 flex-1 truncate" x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms>{{ $item['label'] }}</span>

                            <!-- Tooltip when collapsed -->
                            <span
                                x-show="sidebarCollapsed"
                                class="pointer-events-none absolute left-full top-1/2 z-50 ml-3 -translate-y-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white opacity-0 shadow-lg transition duration-150 group-hover:opacity-100"
                            >
                                {{ $item['label'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="mt-6 h-px bg-slate-200/70"></div>

        <div class="mt-4 rounded-2xl border border-slate-200/70 bg-white/60 p-3 shadow-sm" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
            <div class="text-xs font-semibold text-slate-600">{{ __('Tip') }}</div>
            <div class="mt-1 text-xs text-slate-600">{{ __('Keep content fresh — update banners and posts weekly.') }}</div>
        </div>
    </nav>
</aside>

