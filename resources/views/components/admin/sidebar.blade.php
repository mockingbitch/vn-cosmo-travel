@php
    $sections = [
        [
            'title' => __('General'),
            'items' => [
                ['label' => __('Dashboard'), 'route' => 'admin.dashboard', 'icon' => 'home'],
                ['label' => __('admin.guide.menu'), 'route' => 'admin.guide', 'icon' => 'book'],
                ['label' => __('profile'), 'route' => 'admin.profile.edit', 'icon' => 'user'],
            ],
        ],
        [
            'title' => __('customers'),
            'items' => [
                ['label' => __('bookings'), 'route' => 'admin.bookings.index', 'icon' => 'inbox'],
            ],
        ],
        [
            'title' => __('Content'),
            'items' => [
                ['label' => __('tours'), 'route' => 'admin.tours.index', 'icon' => 'tours'],
                ['label' => __('destinations'), 'route' => 'admin.destinations.index', 'icon' => 'map'],
                ['label' => __('blog'), 'route' => 'admin.posts.index', 'icon' => 'document'],
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
                        ['label' => __('contact'), 'route' => 'admin.settings.contact.edit', 'icon' => 'phone'],
                        ['label' => __('Social links'), 'route' => 'admin.settings.social.edit', 'icon' => 'share'],
                        ['label' => __('admin.settings.home_why.section'), 'route' => 'admin.settings.homeWhy.edit', 'icon' => 'sparkles'],
                        ['label' => __('admin.settings.testimonials.section'), 'route' => 'admin.settings.testimonials.edit', 'icon' => 'quotes'],
                        ['label' => __('nav.primary.about_us'), 'route' => 'admin.about.edit', 'icon' => 'about'],
                        ['label' => __('Hero Banners'), 'route' => 'admin.banners.edit', 'icon' => 'photo'],
                    ],
                ],
                [
                    'label' => __('admin.sidebar.other_services'),
                    'icon' => 'grid-apps',
                    'children' => [
                        ['label' => __('nav.sub.airport_taxi'), 'route' => 'admin.service-pages.edit', 'route_params' => ['type' => 'airport-taxi'], 'icon' => 'car'],
                        ['label' => __('nav.sub.visa_service'), 'route' => 'admin.service-pages.edit', 'route_params' => ['type' => 'visa-service'], 'icon' => 'document'],
                        ['label' => __('nav.sub.bus_flight_train'), 'route' => 'admin.service-pages.edit', 'route_params' => ['type' => 'bus-flight-train-ticket'], 'icon' => 'ticket'],
                        ['label' => __('nav.sub.sim_card'), 'route' => 'admin.service-pages.edit', 'route_params' => ['type' => 'sim-card'], 'icon' => 'sim'],
                    ],
                ],
            ],
        ];

        $sections[] = [
            'title' => __('team'),
            'items' => [
                ['label' => __('users'), 'route' => 'admin.users.index', 'icon' => 'users'],
            ],
        ];
    }

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
                <span class="block truncate text-xs font-medium text-slate-500">{{ __('admin') }}</span>
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
                <span class="inline-flex transition-transform duration-200" :class="sidebarCollapsed ? 'rotate-180' : ''">
                    <x-icon name="arrow-left" size="md" />
                </span>
            </button>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 p-2 text-slate-700 shadow-sm transition hover:bg-slate-50 lg:hidden"
                @click="sidebarOpen = false"
                aria-label="Close sidebar"
            >
                <x-icon name="close" size="md" />
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
                                $firstChildParams = $item['children'][0]['route_params'] ?? [];
                            @endphp
                            {{-- Collapsed: one link to first settings page --}}
                            <a
                                x-show="sidebarCollapsed"
                                href="{{ route($firstChildRoute, $firstChildParams) }}"
                                class="group relative flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200 ease-in-out
                                    {{ $settingsGroupActive ? 'bg-indigo-600/10 text-indigo-700' : 'text-slate-700 hover:bg-slate-100/70 hover:shadow-sm hover:scale-[1.01]' }}"
                                title="{{ $item['label'] }}"
                                @if($settingsGroupActive) aria-current="true" @endif
                            >
                                <span class="absolute left-0 top-1/2 h-6 w-[3px] -translate-y-1/2 rounded-r bg-indigo-600 transition-opacity {{ $settingsGroupActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}"></span>
                                <x-icon
                                    :name="$item['icon']"
                                    size="md"
                                    class="shrink-0 {{ $settingsGroupActive ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-700' }}"
                                />
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
                                    <x-icon
                                        :name="$item['icon']"
                                        size="md"
                                        class="shrink-0 text-slate-400 transition-colors group-hover:text-slate-700 {{ $settingsGroupActive ? 'text-indigo-600' : '' }}"
                                    />
                                    <span class="min-w-0 flex-1">{{ $item['label'] }}</span>
                                    <span class="inline-flex shrink-0 text-slate-400 transition-transform" :class="subOpen ? 'rotate-180' : ''">
                                        <x-icon name="chevron-down" size="sm" />
                                    </span>
                                </button>

                                <div
                                    x-show="subOpen"
                                    x-transition
                                    class="ms-4 mt-0.5 space-y-0.5 rounded-r-xl border-l-2 border-indigo-200/80 bg-slate-50/70 py-2 pe-2 ps-5 sm:ps-6"
                                >
                                    @foreach($item['children'] as $child)
                                        @php
                                            $childActive = request()->routeIs($child['route']);
                                            if ($childActive && ! empty($child['route_params']) && is_array($child['route_params'])) {
                                                foreach ($child['route_params'] as $param => $value) {
                                                    if ((string) request()->route((string) $param) !== (string) $value) {
                                                        $childActive = false;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <a
                                            href="{{ route($child['route'], $child['route_params'] ?? []) }}"
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
                                                <x-icon
                                                    :name="$child['icon']"
                                                    size="sm"
                                                    class="!h-3.5 !w-3.5 shrink-0 {{ $childActive ? 'text-indigo-600' : 'text-slate-400' }}"
                                                />
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

                                <x-icon
                                    :name="$item['icon'] ?? 'home'"
                                    size="md"
                                    class="shrink-0 transition-colors duration-200 {{ $active ? 'text-indigo-700' : 'text-slate-400 group-hover:text-slate-700' }}"
                                />

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

