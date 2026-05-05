@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="{{ $hero->imageUrl() }}"
                alt="{{ __('ui.hero_image_alt') }}"
                class="h-full w-full object-cover"
                loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/35 to-white"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 pb-14 pt-14 sm:px-6 sm:pb-20 sm:pt-20 lg:px-8 lg:pb-24">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white ring-1 ring-white/20">
                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                    {{ __('ui.hero_badge') }}
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
                        <x-icon name="chevron-right" size="sm" />
                    </x-button>
                    <x-button href="#booking" variant="ghost" class="justify-center text-white hover:bg-white/10 focus:ring-white/50">
                        <x-icon name="chevron-down" size="sm" />
                        {{ __('ui.plan_with_us') }}
                    </x-button>
                </div>
            </div>

            <div class="mt-10 max-w-3xl">
                <form action="{{ route('tours.index') }}" method="GET" class="rounded-2xl bg-white/95 p-4 shadow-lg ring-1 ring-slate-200 backdrop-blur sm:p-5">
                    <div class="grid gap-4 sm:grid-cols-3">
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('destination') }}</span>
                            <select
                                name="destination"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('ui.any_destination') }}</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->slug }}">{{ $destination->localizedName() }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('duration') }}</span>
                            <select
                                name="duration"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('any') }}</option>
                                <option value="1-3">{{ __('ui.13_days') }}</option>
                                <option value="4-7">{{ __('ui.47_days') }}</option>
                                <option value="8+">{{ __('ui.8_days') }}</option>
                            </select>
                        </label>

                        <div class="flex items-end">
                            <x-button type="submit" variant="primary" class="w-full justify-center">
                                {{ __('ui.find_tours') }}
                                <x-icon name="search" size="sm" />
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
                <x-button href="{{ route('tours.index') }}" variant="secondary">
                    {{ __('ui.view_all') }}
                    <x-icon name="chevron-right" size="sm" />
                </x-button>
            </div>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($featuredTours as $vm)
                <x-tour-card :vm="$vm" />
            @endforeach
        </div>

        <div class="mt-8 sm:hidden">
            <x-button href="{{ route('tours.index') }}" variant="secondary" class="w-full justify-center">
                {{ __('ui.view_all_tours') }}
                <x-icon name="chevron-right" size="sm" />
            </x-button>
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
                $whyIconNames = ['bolt', 'tag', 'sparkles', 'shield-check'];
            @endphp

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($homeWhy['items'] as $idx => $item)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-900 text-white">
                                <x-icon :name="$whyIconNames[$idx % count($whyIconNames)]" size="md" />
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
                    <x-button href="{{ route('blog.index') }}" variant="secondary">
                        {{ __('ui.view_all') }}
                        <x-icon name="chevron-right" size="sm" />
                    </x-button>
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
                    <div class="mt-3 text-sm font-semibold text-slate-900">{{ __('ui.explore_tours_arrow') }}</div>
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
                            <x-icon name="tours" size="sm" />
                            {{ __('ui.browse_tours') }}
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
                                    <x-icon name="envelope" size="md" />
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('email') }}</div>
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
                                    <x-icon name="phone" size="md" />
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('phone') }}</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-900 group-hover:underline">{{ $siteContact->phone() }}</div>
                                </div>
                            </a>
                        @endif

                        @if($siteContact->address())
                            <div class="flex items-start gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-slate-900 text-white">
                                    <x-icon name="map" size="md" />
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('address') }}</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-900">{{ $siteContact->address() }}</div>
                                </div>
                            </div>
                        @endif

                        @if(count($siteContact->socialLinks()) > 0)
                            <div class="flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                <span class="mr-1 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('ui.follow_us') }}</span>
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
