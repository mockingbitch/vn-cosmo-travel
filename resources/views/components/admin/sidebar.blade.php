@php
    $sections = [
        [
            'title' => __('General'),
            'items' => [
                ['label' => __('Dashboard'), 'route' => 'admin.dashboard', 'icon' => 'home'],
                ['label' => __('admin.guide.menu'), 'route' => 'admin.guide', 'icon' => 'book'],
                ['label' => __('Profile'), 'route' => 'admin.profile.edit', 'icon' => 'user'],
            ],
        ],
        [
            'title' => __('Customers'),
            'items' => [
                ['label' => __('Bookings'), 'route' => 'admin.bookings.index', 'icon' => 'inbox'],
            ],
        ],
        [
            'title' => __('Content'),
            'items' => [
                ['label' => __('Tours'), 'route' => 'admin.tours.index', 'icon' => 'tours'],
                ['label' => __('Destinations'), 'route' => 'admin.destinations.index', 'icon' => 'map'],
                ['label' => __('Blog'), 'route' => 'admin.posts.index', 'icon' => 'document'],
                ['label' => __('Media'), 'route' => 'admin.media.index', 'icon' => 'folder'],
            ],
        ],
    ];

    if (auth()->check() && auth()->user()->canManageUsers()) {
        $sections[] = [
            'title' => null,
            'items' => [
                [
                    'label' => __('Settings'),
                    'icon' => 'cog',
                    'children' => [
                        ['label' => __('Website'), 'route' => 'admin.settings.general.edit', 'icon' => 'cog'],
                        ['label' => __('Contact'), 'route' => 'admin.settings.contact.edit', 'icon' => 'phone'],
                        ['label' => __('Social links'), 'route' => 'admin.settings.social.edit', 'icon' => 'share'],
                        ['label' => __('Home why section'), 'route' => 'admin.settings.homeWhy.edit', 'icon' => 'sparkles'],
                        ['label' => __('Hero Banners'), 'route' => 'admin.banners.edit', 'icon' => 'photo'],
                    ],
                ],
            ],
        ];

        $sections[] = [
            'title' => __('Team'),
            'items' => [
                ['label' => __('Users'), 'route' => 'admin.users.index', 'icon' => 'users'],
            ],
        ];
    }

    $icon = function (string $name): string {
        return match ($name) {
            'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75V19.5a2.25 2.25 0 0 0 2.25 2.25h3.75v-6a2.25 2.25 0 0 1 2.25-2.25h.75a2.25 2.25 0 0 1 2.25 2.25v6h3.75A2.25 2.25 0 0 0 19.5 19.5V9.75"/>',
            'book' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6.75 4.5v13.36c1.181-.693 2.548-1.065 3.958-1.065 1.41 0 2.777.372 3.958 1.065V4.5A8.967 8.967 0 0 0 12 6.042Zm0 0c1.79-.965 3.684-.964 5.458 0v13.361a9.026 9.026 0 0 0-5.458 0V6.042Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.75 4.5v13.36c-.886-.521-1.845-.807-2.833-.864V5.364c.988-.057 1.947-.343 2.833-.864Zm-13.5 0c.886.521 1.845.807 2.833.864v11.332c-.988.057-1.947.343-2.833.864V4.5Z"/>',
            'user' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 0 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>',
            'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>',
            'cog' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 1 12.75-5.303M19.5 12a7.5 7.5 0 0 1-12.75 5.303M12 9.75A2.25 2.25 0 1 0 12 14.25 2.25 2.25 0 0 0 12 9.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25v1.5m0 16.5v1.5M3.75 12h-1.5m18 0h1.5M5.47 5.47 4.41 4.41m15.18 15.18 1.06 1.06M18.53 5.47l1.06-1.06M4.41 19.59l1.06-1.06"/>',
            'photo' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.159 2.159M3 18.75h18A2.25 2.25 0 0 0 23.25 16.5V6A2.25 2.25 0 0 0 21 3.75H3A2.25 2.25 0 0 0 .75 6v10.5A2.25 2.25 0 0 0 3 18.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h.008v.008H7.5V8.25Z"/>',
            'document' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m0 11.25h7.5m-7.5 3h7.5m-7.5 3h4.5M6.75 2.25H10.5A2.25 2.25 0 0 1 12.75 4.5v2.25A2.25 2.25 0 0 0 15 9h2.25A2.25 2.25 0 0 1 19.5 11.25v9A2.25 2.25 0 0 1 17.25 22.5h-9A2.25 2.25 0 0 1 6 20.25V4.5A2.25 2.25 0 0 1 8.25 2.25Z"/>',
            'folder' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V6A2.25 2.25 0 0 1 4.5 3.75h4.127c.597 0 1.17.237 1.592.659l.622.622c.422.422.995.659 1.591.659H19.5A2.25 2.25 0 0 1 21.75 7.5v5.25m-19.5 0h19.5m-19.5 0v6.75A2.25 2.25 0 0 0 4.5 21.75h15A2.25 2.25 0 0 0 21.75 19.5v-6.75"/>',
            'tours' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5M3.75 9.75h16.5M3.75 14.25h16.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25h-1.5A1.5 1.5 0 0 0 1.5 6.75V18A1.5 1.5 0 0 0 3 19.5h1.5" />',
            'map' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>',
            'phone' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25z"/>',
            'share' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.935-2.186 2.25 2.25 0 0 0-3.935 2.186Z"/>',
            'sparkles' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"/>',
            'inbox' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z"/>',
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
                @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('admin.sidebarCollapsed', sidebarCollapsed ? '1' : '0')"
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

    <nav class="flex-1 overflow-y-auto px-3 pb-4 pt-1 [scrollbar-width:thin] [scrollbar-color:rgb(203,213,225)_transparent]">
        @foreach($sections as $section)
            <div class="mt-4 first:mt-0">
                @if(filled($section['title'] ?? null))
                    <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400" x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms>
                        {{ $section['title'] }}
                    </div>
                @endif

                <div class="grid gap-1">
                    @foreach($section['items'] as $item)
                        @if(!empty($item['children']))
                            @php
                                $settingsGroupActive = false;
                                foreach ($item['children'] as $childRoute) {
                                    $rn = (string) ($childRoute['route'] ?? '');
                                    if ($rn === '') {
                                        continue;
                                    }
                                    $pattern = \Illuminate\Support\Str::contains($rn, '.')
                                        ? \Illuminate\Support\Str::beforeLast($rn, '.').'.*'
                                        : $rn.'*';
                                    if (request()->routeIs($pattern)) {
                                        $settingsGroupActive = true;
                                        break;
                                    }
                                }
                                $firstChildRoute = $item['children'][0]['route'] ?? 'admin.dashboard';
                            @endphp
                            {{-- Collapsed: one link to first settings page --}}
                            <a
                                x-show="sidebarCollapsed"
                                href="{{ route($firstChildRoute) }}"
                                class="group relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 ease-in-out
                                    {{ $settingsGroupActive ? 'bg-indigo-600/10 text-indigo-700' : 'text-slate-700 hover:bg-slate-100/70 hover:shadow-sm hover:scale-[1.01]' }}"
                                title="{{ $item['label'] }}"
                                @if($settingsGroupActive) aria-current="true" @endif
                            >
                                <span class="absolute left-0 top-1/2 h-6 w-[3px] -translate-y-1/2 rounded-r bg-indigo-600 transition-opacity {{ $settingsGroupActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                                <svg class="h-5 w-5 shrink-0 {{ $settingsGroupActive ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-700' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    {!! $icon($item['icon']) !!}
                                </svg>
                                <span class="pointer-events-none absolute left-full top-1/2 z-50 ml-3 -translate-y-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white opacity-0 shadow-lg transition duration-150 group-hover:opacity-100">
                                    {{ $item['label'] }}
                                </span>
                            </a>

                            <div
                                x-show="!sidebarCollapsed"
                                x-data="{ subOpen: {{ $settingsGroupActive ? 'true' : 'false' }} }"
                                class="grid gap-0.5"
                            >
                                <button
                                    type="button"
                                    @click="subOpen = !subOpen"
                                    class="group relative flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-left text-sm font-semibold transition-all duration-200
                                        {{ $settingsGroupActive
                                            ? 'text-indigo-800'
                                            : 'text-slate-700' }} hover:bg-slate-100/70"
                                    :aria-expanded="subOpen"
                                >
                                    <span class="absolute left-0 top-1/2 h-6 w-[3px] -translate-y-1/2 rounded-r bg-indigo-600 transition-opacity {{ $settingsGroupActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                                    <svg
                                        class="h-5 w-5 shrink-0 text-slate-400 transition-colors group-hover:text-slate-700 {{ $settingsGroupActive ? 'text-indigo-600' : '' }}"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        {!! $icon($item['icon']) !!}
                                    </svg>
                                    <span class="min-w-0 flex-1">{{ $item['label'] }}</span>
                                    <svg
                                        class="h-4 w-4 shrink-0 text-slate-400 transition-transform"
                                        :class="subOpen ? 'rotate-180' : ''"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                <div
                                    x-show="subOpen"
                                    x-transition
                                    class="ms-4 mt-0.5 space-y-0.5 rounded-r-xl border-l-2 border-indigo-200/80 bg-slate-50/70 py-2 pe-2 ps-5 sm:ps-6"
                                >
                                    @foreach($item['children'] as $child)
                                        @php
                                            $childActive = request()->routeIs($child['route']);
                                        @endphp
                                        <a
                                            href="{{ route($child['route']) }}"
                                            @if($childActive) aria-current="page" @endif
                                            class="group relative flex items-center gap-2.5 rounded-lg border border-transparent py-1.5 pe-1.5 ps-1.5 text-sm font-medium transition
                                                {{ $childActive
                                                    ? 'bg-white shadow-sm ring-1 ring-indigo-200/60'
                                                    : 'text-slate-600 hover:border-slate-200/80 hover:bg-white/80 hover:text-slate-900' }}"
                                        >
                                            <span
                                                class="absolute start-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-e bg-indigo-500/90 {{ $childActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"
                                            ></span>
                                            <span class="ps-0.5">
                                                <svg
                                                    class="h-3.5 w-3.5 shrink-0 {{ $childActive ? 'text-indigo-600' : 'text-slate-400' }}"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                >
                                                    {!! $icon($child['icon']) !!}
                                                </svg>
                                            </span>
                                            <span class="min-w-0 flex-1 truncate leading-snug pe-0.5">{{ $child['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php
                                $isDisabled = empty($item['route'] ?? null);
                                $routeName = (string) ($item['route'] ?? '');
                                $activePattern = $isDisabled
                                    ? ''
                                    : (str_ends_with($routeName, '.index')
                                        ? \Illuminate\Support\Str::beforeLast($routeName, '.index').'.*'
                                        : $routeName.'*');
                                $active = !$isDisabled && request()->routeIs($activePattern);
                            @endphp

                            <a
                                href="{{ $isDisabled ? '#' : route($item['route']) }}"
                                class="group relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 ease-in-out
                                    {{ $active
                                        ? 'bg-indigo-600/10 text-indigo-700'
                                        : ($isDisabled ? 'cursor-not-allowed opacity-50' : 'text-slate-700 hover:bg-slate-100/70 hover:shadow-sm hover:scale-[1.01]') }}"
                                @if($active) aria-current="true" @endif
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
                                    {!! $icon($item['icon'] ?? 'home') !!}
                                </svg>

                                <span class="min-w-0 flex-1 truncate" x-show="!sidebarCollapsed" x-transition.opacity.duration.150ms>{{ $item['label'] }}</span>

                                <span
                                    x-show="sidebarCollapsed"
                                    class="pointer-events-none absolute left-full top-1/2 z-50 ml-3 -translate-y-1/2 whitespace-nowrap rounded-xl bg-slate-900 px-3 py-2 text-xs font-semibold text-white opacity-0 shadow-lg transition duration-150 group-hover:opacity-100"
                                >
                                    {{ $item['label'] }}
                                </span>
                            </a>
                        @endif
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

