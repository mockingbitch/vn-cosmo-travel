@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=2400&q=80"
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
                    {{ __('Hero title') }}
                </h1>
                <p class="mt-4 text-base leading-7 text-white/90 sm:text-lg">
                    {{ __('Hero subtitle') }}
                </p>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-button href="{{ route('tours.index') }}" variant="secondary" class="justify-center bg-white hover:bg-white/90 focus:ring-white/50">
                        {{ __('Explore Tours') }}
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

    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <x-section-title
                :title="$homeWhy['title']"
                :subtitle="$homeWhy['subtitle']"
                class="max-w-2xl"
            />

            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($homeWhy['items'] as $item)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-xl bg-slate-900 text-white">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <x-section-title
            :title="__('home.testimonials.title')"
            :subtitle="__('home.testimonials.subtitle')"
            class="max-w-2xl"
        />

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            @foreach([1, 2, 3] as $i)
                <figure class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <blockquote class="text-sm leading-7 text-slate-700">
                        “{{ __('home.testimonials.quote_' . $i) }}”
                    </blockquote>
                    <figcaption class="mt-4 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ __('home.testimonials.author_' . $i) }}</div>
                            <div class="text-xs text-slate-500">{{ __('home.testimonials.meta_' . $i) }}</div>
                        </div>
                        <div class="text-xs font-semibold text-amber-600">★★★★★</div>
                    </figcaption>
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
@endsection
