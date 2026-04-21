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

    <header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/80 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="grid h-9 w-9 place-items-center rounded-xl bg-slate-900 text-white">
                        <span class="text-sm font-semibold">VC</span>
                    </div>
                    <div class="leading-tight">
                        <div class="text-sm font-semibold">{{ __('Brand name') }}</div>
                        <div class="text-xs text-slate-500">{{ __('Tailor-made Vietnam tours') }}</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-7 text-sm font-medium text-slate-700 md:flex">
                    <a class="hover:text-slate-900" href="{{ route('home') }}">{{ __('Home') }}</a>
                    <a class="hover:text-slate-900" href="{{ route('tours.index') }}">{{ __('Tours') }}</a>
                    <a class="hover:text-slate-900" href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
                    <a class="hover:text-slate-900" href="{{ route('home') }}#destinations">{{ __('Destinations') }}</a>
                    <a class="hover:text-slate-900" href="{{ route('home') }}#contact">{{ __('Contact') }}</a>
                </nav>

                <div class="hidden items-center gap-3 md:flex">
                    <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-2 py-1 text-xs font-semibold text-slate-700">
                        @php($supported = (array) config('locales.supported', []))
                        @foreach($supported as $key => $meta)
                            <a
                                href="{{ route('language.switch', ['locale' => $key]) }}"
                                class="rounded-lg px-2 py-1 {{ app()->getLocale() === $key ? 'bg-slate-900 text-white' : 'hover:bg-slate-50' }}"
                            >{{ $meta['label'] ?? strtoupper($key) }}</a>
                        @endforeach
                    </div>
                    <x-button href="{{ route('tours.index') }}" variant="primary">{{ __('Explore Tours') }}</x-button>
                </div>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 p-2 text-slate-700 hover:bg-slate-50 md:hidden"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-controls="mobile-menu"
                >
                    <span class="sr-only">{{ __('Open menu') }}</span>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" x-show="open" x-transition class="border-t border-slate-200 md:hidden">
            <div class="mx-auto max-w-7xl px-4 py-4">
                <div class="grid gap-2 text-sm font-medium text-slate-700">
                    <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('home') }}">{{ __('Home') }}</a>
                    <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('tours.index') }}">{{ __('Tours') }}</a>
                    <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
                    <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('home') }}#destinations">{{ __('Destinations') }}</a>
                    <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('home') }}#contact">{{ __('Contact') }}</a>
                    <div class="flex items-center gap-2 px-3 py-2">
                        @foreach((array) config('locales.supported', []) as $key => $meta)
                            <a
                                href="{{ route('language.switch', ['locale' => $key]) }}"
                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold {{ app()->getLocale() === $key ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 hover:bg-slate-50' }}"
                            >{{ $meta['label'] ?? strtoupper($key) }}</a>
                        @endforeach
                    </div>
                    <div class="pt-2">
                        <x-button class="w-full justify-center" href="{{ route('tours.index') }}" variant="primary">{{ __('Explore Tours') }}</x-button>
                    </div>
                </div>
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

