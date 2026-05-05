<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        /** @var \App\ViewModels\SeoViewModel|null $seo */
        $pageTitle = $seo?->title ?? config('app.name');
        $pageDescription = $seo?->description ?? __('ui.default_meta_description');
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

    <x-favicon />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-900 antialiased">
    <a href="#content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-slate-900 focus:shadow">
        {{ __('ui.skip_to_content') }}
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
                    <div class="hidden min-w-0 max-w-[14rem] leading-tight sm:block md:max-w-[18rem]">
                        <div class="truncate text-sm font-semibold">{{ __('ui.brand_name') }}</div>
                        @if($siteContact->phone())
                            @php
                                $headerWaHref = $siteContact->phoneChatHref();
                                $headerPhone = $siteContact->phone();
                            @endphp
                            <a
                                href="{{ $headerWaHref }}"
                                class="mt-0.5 inline-flex max-w-full items-center gap-1.5 truncate text-xs font-medium text-emerald-700 hover:text-emerald-800"
                                aria-label="{{ __('header.whatsapp_aria', ['phone' => $headerPhone]) }}"
                            >
                                <x-icon name="whatsapp" size="sm" class="shrink-0 text-emerald-600" />
                                <span class="truncate underline-offset-2 hover:underline">{{ $headerPhone }}</span>
                            </a>
                        @endif
                    </div>
                </a>

                <x-site.nav-primary :primary="$mainNav['primary']" :dropdown-panels="$mainNav['dropdownPanels'] ?? []" />

                <div class="flex min-w-0 shrink-0 items-center gap-1.5 sm:gap-2.5">
                    <x-site.language-switcher />
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-700 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2 lg:hidden"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen.toString()"
                        aria-controls="mobile-menu"
                    >
                        <span class="sr-only" x-text="mobileOpen ? '{{ __('a11y.close_menu') }}' : '{{ __('nav.header.open_menu') }}'"></span>
                        <x-icon name="menu" size="md" />
                    </button>
                </div>
            </div>
        </div>

        @php
            $dailyMega = $mainNav['dailyMega'] ?? [];
            $showMegaDaily = filled($dailyMega['rows'] ?? []) || filled($dailyMega['featured'] ?? null);
        @endphp
        @if($showMegaDaily)
            <x-site.mega-menu-daily :daily="$dailyMega" />
        @endif

        <div
            id="mobile-menu"
            x-cloak
            x-show="mobileOpen"
            x-transition
            class="border-t border-slate-200 lg:hidden"
            role="region"
            aria-label="{{ __('nav.header.main_navigation') }}"
        >
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
                            <div class="text-xs text-slate-500">{{ __('ui.crafted_vietnam_experiences') }}</div>
                        </div>
                    </div>
                    <p class="mt-4 max-w-md text-sm text-slate-600">
                        {{ __('ui.footer_intro') }}
                    </p>
                </div>

                <div class="grid gap-8 sm:grid-cols-2">
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">{{ __('ui.quick_links') }}</h2>
                        <nav aria-label="{{ __('ui.quick_links') }}" class="mt-3 grid gap-2 text-sm text-slate-600">
                            <a class="rounded hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2" href="{{ route('tours.index') }}">{{ __('tours') }}</a>
                            <a class="rounded hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2" href="{{ route('blog.index') }}">{{ __('blog') }}</a>
                            <a class="rounded hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2" href="{{ route('home') }}#destinations">{{ __('destinations') }}</a>
                        </nav>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">{{ __('contact') }}</h2>
                        <div class="mt-3 grid gap-2 text-sm text-slate-600">
                            @if($siteContact->email())
                                <div>{{ __('email') }}:
                                    <a class="font-medium text-slate-900 hover:underline" href="mailto:{{ $siteContact->email() }}">{{ $siteContact->email() }}</a>
                                </div>
                            @endif
                            @if($siteContact->phone())
                                @php
                                    $footerPhoneHref = $siteContact->phoneChatHref();
                                @endphp
                                <div>{{ __('phone') }}:
                                    <a class="font-medium text-slate-900 hover:underline" href="{{ $footerPhoneHref }}" @if(str_starts_with($footerPhoneHref, 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $siteContact->phone() }}</a>
                                </div>
                            @endif
                            @if($siteContact->address())
                                <div>{{ __('address') }}: <span class="font-medium text-slate-900">{{ $siteContact->address() }}</span></div>
                            @endif
                            @if(count($siteContact->socialLinks()) > 0)
                                <div class="flex flex-wrap gap-3 pt-1">
                                    @foreach($siteContact->socialLinks() as $link)
                                        <a class="hover:text-slate-900" href="{{ $link['url'] }}" target="_blank" rel="noopener" aria-label="{{ $link['label'] }}">{{ $link['label'] }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <h2 class="text-sm font-semibold text-slate-900">{{ __('ui.plan_your_trip_with_us') }}</h2>
                    <p class="mt-2 text-sm text-slate-600">{{ __('ui.footer_cta_blurb') }}</p>
                    <div class="mt-4">
                        <x-button href="{{ route('tours.index') }}" variant="primary" class="w-full justify-center">
                            <x-icon name="map" size="sm" />
                            {{ __('ui.get_started') }}
                        </x-button>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-col gap-2 border-t border-slate-200 pt-6 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <div>© {{ date('Y') }} {{ __('ui.brand_name') }}. {{ __('ui.all_rights_reserved') }}</div>
                <div class="flex gap-4">
                    <a class="hover:text-slate-700" href="#">{{ __('privacy') }}</a>
                    <a class="hover:text-slate-700" href="#">{{ __('terms') }}</a>
                    <a class="hover:text-slate-700" href="{{ url('/sitemap.xml') }}">{{ __('sitemap') }}</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

