<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        /** @var \App\ViewModels\SeoViewModel|null $seo */
        $pageTitle = $seo?->title ?? config('app.name');
        $pageDescription = $seo?->description ?? __('Default meta description');
        $canonical = $seo?->canonical ?? url()->current();
        $ogImage = $seo?->image ?? 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=80';
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased">
    <a href="#content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-slate-900 focus:shadow">
        {{ __('Skip to content') }}
    </a>

    <header
        x-data="mainSiteNav()"
        @keydown.escape.window="closeAll()"
        class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur"
    >
        <div class="mx-auto max-w-7xl overflow-x-visible px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-2 overflow-x-visible min-[60rem]:gap-3">
                <a href="{{ route('home') }}" class="flex min-w-0 shrink-0 items-center gap-2">
                    <div class="grid h-9 w-9 place-items-center rounded-xl bg-slate-900 text-white">
                        <span class="text-sm font-semibold">VC</span>
                    </div>
                    <div class="hidden leading-tight sm:block">
                        <div class="text-sm font-semibold">{{ __('Brand name') }}</div>
                        <div class="text-xs text-slate-500">{{ __('Tailor-made Vietnam tours') }}</div>
                    </div>
                </a>

                <x-site.nav-primary :primary="$mainNav['primary']" :cruise="$mainNav['cruise']" />

                <div class="flex min-w-0 shrink-0 items-center gap-1.5 sm:gap-2.5">
                    <x-site.language-switcher />
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-700 hover:bg-slate-50 lg:hidden"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen.toString()"
                        aria-controls="mobile-menu"
                    >
                        <span class="sr-only">{{ __('Open menu') }}</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <x-site.mega-menu-daily :daily="$mainNav['dailyMega']" />

        <div id="mobile-menu" x-cloak x-show="mobileOpen" x-transition class="border-t border-slate-200 lg:hidden">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6">
                <x-site.nav-mobile :mainNav="$mainNav" />
            </div>
        </div>
    </header>

    <main id="content">
        @yield('content')
    </main>

    <footer id="contact" class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-3">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-900 text-white">
                            <span class="text-sm font-semibold">VC</span>
                        </div>
                        <div>
                            <div class="text-sm font-semibold">vietnamcosmotravel.com</div>
                            <div class="text-xs text-slate-500">{{ __('Crafted Vietnam experiences') }}</div>
                        </div>
                    </div>
                    <p class="mt-4 max-w-md text-sm text-slate-600">
                        {{ __('Footer intro') }}
                    </p>
                </div>

                <div class="grid gap-8 sm:grid-cols-2">
                    <div>
                        <div class="text-sm font-semibold">{{ __('Quick links') }}</div>
                        <div class="mt-3 grid gap-2 text-sm text-slate-600">
                            <a class="hover:text-slate-900" href="{{ route('tours.index') }}">{{ __('Tours') }}</a>
                            <a class="hover:text-slate-900" href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
                            <a class="hover:text-slate-900" href="{{ route('home') }}#destinations">{{ __('Destinations') }}</a>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-semibold">{{ __('Contact') }}</div>
                        <div class="mt-3 grid gap-2 text-sm text-slate-600">
                            <div>{{ __('Email') }}: <span class="font-medium text-slate-900">hello@vietnamcosmotravel.com</span></div>
                            <div>{{ __('Phone') }}: <span class="font-medium text-slate-900">+84 000 000 000</span></div>
                            <div class="flex gap-3 pt-1">
                                <a class="hover:text-slate-900" href="#" aria-label="{{ __('Facebook') }}">{{ __('Facebook') }}</a>
                                <a class="hover:text-slate-900" href="#" aria-label="{{ __('Instagram') }}">{{ __('Instagram') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <div class="text-sm font-semibold">{{ __('Plan your trip with us') }}</div>
                    <p class="mt-2 text-sm text-slate-600">{{ __('Footer CTA blurb') }}</p>
                    <div class="mt-4">
                        <x-button href="{{ route('tours.index') }}" variant="primary" class="w-full justify-center">{{ __('Get started') }}</x-button>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-col gap-2 border-t border-slate-200 pt-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <div>© {{ date('Y') }} {{ __('Brand name') }}. {{ __('All rights reserved.') }}</div>
                <div class="flex gap-4">
                    <a class="hover:text-slate-700" href="#">{{ __('Privacy') }}</a>
                    <a class="hover:text-slate-700" href="#">{{ __('Terms') }}</a>
                    <a class="hover:text-slate-700" href="{{ url('/sitemap.xml') }}">{{ __('Sitemap') }}</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

