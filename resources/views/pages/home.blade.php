@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="{{ $hero->imageUrl() }}"
                alt="{{ __('Hero image alt') }}"
                class="h-full w-full object-cover"
                loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/35 to-white"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 pb-14 pt-14 sm:px-6 sm:pb-20 sm:pt-20 lg:px-8 lg:pb-24">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white ring-1 ring-white/20">
                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                    {{ __('Hero badge') }}
                </div>

                <h1 class="mt-5 text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                    {{ $hero->title() }}
                </h1>
                <p class="mt-4 text-base leading-7 text-white/90 sm:text-lg">
                    {{ $hero->subtitle() }}
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-button href="{{ $hero->ctaLink() }}" variant="secondary" class="justify-center bg-white hover:bg-white/90 focus:ring-white/50">
                        {{ $hero->ctaText() }}
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </x-button>
                    <x-button href="#booking" variant="ghost" class="justify-center text-white hover:bg-white/10 focus:ring-white/50">
                        {{ __('Plan with us') }}
                    </x-button>
                </div>
            </div>

            <div class="mt-10 max-w-3xl">
                <form action="{{ route('tours.index') }}" method="GET" class="rounded-2xl bg-white/95 p-4 shadow-lg ring-1 ring-slate-200 backdrop-blur sm:p-5">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Destination') }}</span>
                            <select
                                name="destination"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('Any destination') }}</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->slug }}">{{ $destination->localizedName() }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Duration') }}</span>
                            <select
                                name="duration"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('Any') }}</option>
                                <option value="1-3">{{ __('1–3 days') }}</option>
                                <option value="4-7">{{ __('4–7 days') }}</option>
                                <option value="8+">{{ __('8+ days') }}</option>
                            </select>
                        </label>

                        <div class="flex items-end">
                            <x-button type="submit" variant="primary" class="w-full justify-center">
                                {{ __('Find tours') }}
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                </svg>
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-6">
            <x-section-title
                :title="__('home.featured.title')"
                :subtitle="__('home.featured.subtitle')"
            />
            <div class="hidden sm:block">
                <x-button href="{{ route('tours.index') }}" variant="secondary">{{ __('View all') }}</x-button>
            </div>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($featuredTours as $vm)
                <x-tour-card :vm="$vm" />
            @endforeach
        </div>

        <div class="mt-8 sm:hidden">
            <x-button href="{{ route('tours.index') }}" variant="secondary" class="w-full justify-center">{{ __('View all tours') }}</x-button>
        </div>
    </section>

    <section id="why-us" class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <x-section-title
                :title="$homeWhy['title']"
                :subtitle="$homeWhy['subtitle']"
                class="max-w-2xl"
            />

            @php
                // Icon paths cycle through the 4 cards by index for visual variety.
                $whyIcons = [
                    // bolt — Fast response
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5 13.5 3l-1.5 7.5h6.75L8.25 21l1.5-7.5H3.75Z" />',
                    // tag — Clear pricing
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />',
                    // sparkles — Curated itineraries
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />',
                    // shield-check — Secure booking
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 5.25-4.5 9-9 9s-9-3.75-9-9c0-1.5.5-3 1-4 1.5-3 5-5 8-5s6.5 2 8 5c.5 1 1 2.5 1 4Z" />',
                ];
            @endphp

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($homeWhy['items'] as $idx => $item)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-900 text-white">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    {!! $whyIcons[$idx % count($whyIcons)] !!}
                                </svg>
                            </div>
                            <div class="text-sm font-semibold text-slate-900">{{ $item['title'] }}</div>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="testimonials" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <x-section-title
            :title="$testimonials['title']"
            :subtitle="$testimonials['subtitle']"
            class="max-w-2xl"
        />

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            @foreach($testimonials['items'] as $card)
                @php
                    $sceneUrl = $card['image_url'] ?? '';
                @endphp
                <figure class="flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="aspect-[16/10] w-full shrink-0 overflow-hidden bg-slate-100">
                        @if($sceneUrl !== '')
                            <img
                                src="{{ $sceneUrl }}"
                                alt="{{ $card['scene_alt'] ?? '' }}"
                                width="960"
                                height="600"
                                loading="lazy"
                                decoding="async"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="h-full w-full object-cover object-center transition duration-300 hover:scale-[1.02]"
                            />
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <blockquote class="flex-1 text-sm leading-7 text-slate-700">
                            “{{ $card['quote'] }}”
                        </blockquote>
                        <figcaption class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-semibold text-slate-900">{{ $card['author'] }}</div>
                                <div class="text-xs text-slate-500">{{ $card['meta'] }}</div>
                            </div>
                            <div class="shrink-0 text-xs font-semibold tracking-tight text-amber-600" aria-hidden="true">★★★★★</div>
                        </figcaption>
                    </div>
                </figure>
            @endforeach
        </div>
    </section>

    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-6">
                <x-section-title
                    :title="__('home.blog.title')"
                    :subtitle="__('home.blog.subtitle')"
                />
                <div class="hidden sm:block">
                    <x-button href="{{ route('blog.index') }}" variant="secondary">{{ __('View all') }}</x-button>
                </div>
            </div>

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($latestPosts as $vm)
                    <x-blog-card :vm="$vm" />
                @endforeach
            </div>
        </div>
    </section>

    <section id="destinations" class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <x-section-title
            :title="__('home.destinations.title')"
            :subtitle="__('home.destinations.subtitle')"
            class="max-w-2xl"
        />

        <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($popularDestinations as $destination)
                <a
                    href="{{ route('destinations.show', $destination) }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="text-sm font-semibold text-slate-900 group-hover:underline">{{ $destination->localizedName() }}</div>
                    <div class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $destination->description }}</div>
                    <div class="mt-3 text-sm font-semibold text-slate-900">{{ __('Explore tours arrow') }}</div>
                </a>
            @empty
                <p class="text-sm text-slate-600 sm:col-span-2 lg:col-span-4">{{ __('home.destinations.empty') }}</p>
            @endforelse
        </div>
    </section>

    <section id="booking" class="bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-semibold tracking-tight text-white sm:text-4xl">{{ __('home.booking.title') }}</h2>
                    <p class="mt-4 text-base leading-7 text-white/80">
                        {{ __('home.booking.lead') }}
                    </p>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-lg">
                    <div class="text-sm font-semibold text-slate-900">{{ __('home.booking.card_title') }}</div>
                    <p class="mt-1 text-sm text-slate-600">{{ __('home.booking.card_text') }}</p>
                    <div class="mt-4">
                        <x-button href="{{ route('tours.index') }}" variant="primary" class="w-full justify-center">
                            {{ __('Browse tours') }}
                        </x-button>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">
                        {{ __('home.booking.tip') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($siteContact->hasContactInfo() || $siteContact->hasMap())
        <section id="get-in-touch" class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ __('home.contact.title') }}</h2>
                    <p class="mt-3 text-base leading-7 text-slate-600">{{ __('home.contact.lead') }}</p>
                </div>

                <div class="mt-8 grid gap-6 lg:grid-cols-5">
                    <div class="grid gap-4 lg:col-span-2">
                        @if($siteContact->email())
                            <a
                                href="mailto:{{ $siteContact->email() }}"
                                class="group flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md"
                            >
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-slate-900 text-white">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Email') }}</div>
                                    <div class="mt-1 break-all text-sm font-semibold text-slate-900 group-hover:underline">{{ $siteContact->email() }}</div>
                                </div>
                            </a>
                        @endif

                        @if($siteContact->phone())
                            @php
                                $phoneChatHref = $siteContact->phoneChatHref();
                            @endphp
                            <a
                                href="{{ $phoneChatHref }}"
                                @if(str_starts_with($phoneChatHref, 'http')) target="_blank" rel="noopener noreferrer" @endif
                                class="group flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md"
                            >
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-slate-900 text-white">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Phone') }}</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-900 group-hover:underline">{{ $siteContact->phone() }}</div>
                                </div>
                            </a>
                        @endif

                        @if($siteContact->address())
                            <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-slate-900 text-white">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Address') }}</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-900">{{ $siteContact->address() }}</div>
                                </div>
                            </div>
                        @endif

                        @if(count($siteContact->socialLinks()) > 0)
                            <div class="flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <span class="mr-1 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Follow us') }}</span>
                                @foreach($siteContact->socialLinks() as $link)
                                    <a
                                        href="{{ $link['url'] }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="lg:col-span-3">
                        @if($siteContact->hasMap())
                            <div class="overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
                                <div class="aspect-[16/10] w-full bg-slate-100 [&_iframe]:h-full [&_iframe]:w-full [&_iframe]:border-0">
                                    {!! $siteContact->mapIframe() !!}
                                </div>
                            </div>
                        @else
                            <div class="grid h-full place-items-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center">
                                <div class="text-sm font-semibold text-slate-500">{{ __('home.contact.map_placeholder') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
