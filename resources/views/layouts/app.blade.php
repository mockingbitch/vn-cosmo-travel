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

    <x-favicon />

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
                    <div class="hidden min-w-0 max-w-[14rem] leading-tight sm:block md:max-w-[18rem]">
                        <div class="truncate text-sm font-semibold">{{ __('Brand name') }}</div>
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
                                <svg class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.883 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
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

        @php
            $dailyMega = $mainNav['dailyMega'] ?? [];
            $showMegaDaily = filled($dailyMega['rows'] ?? []) || filled($dailyMega['featured'] ?? null);
        @endphp
        @if($showMegaDaily)
            <x-site.mega-menu-daily :daily="$dailyMega" />
        @endif

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
                            @if($siteContact->email())
                                <div>{{ __('Email') }}:
                                    <a class="font-medium text-slate-900 hover:underline" href="mailto:{{ $siteContact->email() }}">{{ $siteContact->email() }}</a>
                                </div>
                            @endif
                            @if($siteContact->phone())
                                @php
                                    $footerPhoneHref = $siteContact->phoneChatHref();
                                @endphp
                                <div>{{ __('Phone') }}:
                                    <a class="font-medium text-slate-900 hover:underline" href="{{ $footerPhoneHref }}" @if(str_starts_with($footerPhoneHref, 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $siteContact->phone() }}</a>
                                </div>
                            @endif
                            @if($siteContact->address())
                                <div>{{ __('Address') }}: <span class="font-medium text-slate-900">{{ $siteContact->address() }}</span></div>
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

