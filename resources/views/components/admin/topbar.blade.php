@props([
    'title' => 'Admin',
])

<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/80 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-700 hover:bg-slate-50 lg:hidden"
                @click="sidebarOpen = true"
                aria-label="Open sidebar"
            >
                <x-icon name="menu" size="md" />
            </button>

            <div>
                <div class="text-lg font-semibold tracking-tight text-slate-900">{{ $title }}</div>
                <div class="text-xs font-medium text-slate-500">{{ now()->locale(app()->getLocale())->isoFormat('dddd, LL') }}</div>
            </div>
        </div>

        <div class="hidden w-full max-w-md items-center gap-3 md:flex">
            <div class="relative w-full">
                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <x-icon name="search" size="md" />
                </div>
                <input
                    type="search"
                    placeholder="{{ __('placeholder.admin_search') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                />
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('home') }}"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:bg-slate-50"
                aria-label="{{ __('Back to homepage') }}"
                title="{{ __('Back to homepage') }}"
            >
                <x-icon name="home" size="md" />
            </a>

            <div class="relative" x-data="{ langOpen: false }" @click.outside="langOpen = false">
                @php
                    $supportedLocales = (array) config('locales.supported', []);
                    $currentLocale = app()->getLocale();
                    $currentLocaleLabel = $supportedLocales[$currentLocale]['label'] ?? strtoupper((string) $currentLocale);
                @endphp

                <button
                    type="button"
                    class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    @click="langOpen = !langOpen"
                    :aria-expanded="langOpen.toString()"
                    aria-label="{{ __('Language') }}"
                >
                    <x-icon name="globe-alt" size="sm" class="text-slate-500" />
                    <span>{{ $currentLocaleLabel }}</span>
                    <span class="inline-flex text-slate-400">
                        <x-icon name="chevron-down" size="sm" />
                    </span>
                </button>

                <div
                    x-show="langOpen"
                    x-transition.opacity.origin.top.right
                    class="absolute right-0 mt-2 w-44 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg"
                >
                    @foreach($supportedLocales as $key => $meta)
                        <a
                            href="{{ route('language.switch', ['locale' => $key]) }}"
                            class="flex items-center justify-between gap-3 px-4 py-3 text-sm font-semibold {{ $currentLocale === $key ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-50' }}"
                        >
                            <span>{{ $meta['name'] ?? ($meta['label'] ?? strtoupper((string) $key)) }}</span>
                            <span class="text-xs {{ $currentLocale === $key ? 'text-white/80' : 'text-slate-400' }}">{{ $meta['label'] ?? strtoupper((string) $key) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:bg-slate-50"
                aria-label="Notifications"
            >
                <x-icon name="bell" size="md" />
            </button>

            <div class="relative" @click.outside="profileOpen = false">
                <button
                    type="button"
                    class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
                    @click="profileOpen = !profileOpen"
                    :aria-expanded="profileOpen.toString()"
                >
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-slate-900 text-xs font-semibold text-white">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </span>
                    <span class="hidden max-w-[10rem] truncate sm:block">{{ auth()->user()->name ?? __('Account') }}</span>
                    <span class="inline-flex text-slate-400">
                        <x-icon name="chevron-down" size="sm" />
                    </span>
                </button>

                <div
                    x-show="profileOpen"
                    x-transition.opacity.origin.top.right
                    class="absolute right-0 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg"
                >
                    <div class="px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">{{ __('Signed in as') }}</div>
                        <div class="mt-1 truncate text-sm font-semibold text-slate-900">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                    <div class="h-px bg-slate-200"></div>
                    <a class="block px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="{{ route('admin.profile.edit') }}">{{ __('Profile') }}</a>
                    @if(auth()->user()->canManageUsers())
                        <a class="block px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="{{ route('admin.settings.general.edit') }}">{{ __('Settings') }}</a>
                    @endif
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center gap-2 px-4 py-3 text-sm font-semibold text-rose-700 hover:bg-rose-50">
                            <x-icon name="arrow-left" size="sm" />
                            {{ __('Sign out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

