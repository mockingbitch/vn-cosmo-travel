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
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div>
                <div class="text-lg font-semibold tracking-tight text-slate-900">{{ $title }}</div>
                <div class="text-xs font-medium text-slate-500">{{ now()->locale(app()->getLocale())->isoFormat('dddd, LL') }}</div>
            </div>
        </div>

        <div class="hidden w-full max-w-md items-center gap-3 md:flex">
            <div class="relative w-full">
                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                    </svg>
                </div>
                <input
                    type="search"
                    placeholder="{{ __('Search') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-10 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                />
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button
                type="button"
                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:bg-slate-50"
                aria-label="Notifications"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
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
                    <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.24 4.5a.75.75 0 0 1-1.08 0l-4.24-4.5a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd"/>
                    </svg>
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
                    <a class="block px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50" href="{{ route('admin.settings.edit') }}">{{ __('Settings') }}</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-3 text-left text-sm font-semibold text-rose-700 hover:bg-rose-50">{{ __('Sign out') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

