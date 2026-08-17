@extends('layouts.app')

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="min-w-0 flex-1">
                    <x-tour-book-now-badge class="mb-3" />
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 sm:text-sm">{{ $tour->destination?->localizedName() }}</div>
                    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl lg:text-4xl">{{ $tour->title }}</h1>
                    <p class="mt-2 hidden max-w-3xl text-sm leading-7 text-slate-600 sm:block sm:text-base">
                        {{ \Illuminate\Support\Str::limit(strip_tags((string) $tour->description), 220) }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-900 sm:px-4 sm:py-2 sm:text-sm">
                        @if((int) $tour->duration === 1)
                            {{ __('ui.1_day') }}
                        @else
                            {{ __(':count days', ['count' => $tour->duration]) }}
                        @endif
                    </div>
                    <div class="rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white sm:px-4 sm:py-2 sm:text-sm">
                        {{ $tour->formattedPrice() }}
                        <span class="text-[10px] font-medium text-white/80 sm:text-xs">{{ __('ui.per_person') }}</span>
                    </div>
                    <a
                        href="#tour-booking"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-md animate-book-now-blink sm:text-sm lg:hidden"
                    >
                        {{ __('ui.book_now') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-24 pt-4 sm:px-6 sm:py-6 lg:px-8 lg:pb-10 lg:pt-10">
        <div class="grid gap-6 lg:grid-cols-12 lg:gap-10">
            <aside id="tour-booking" class="order-2 scroll-mt-20 lg:order-2 lg:col-span-4">
                <div class="lg:sticky lg:top-24">
                    <x-tour-booking-panel :tour="$tour" />
                </div>
            </aside>

            <div class="order-1 lg:order-1 lg:col-span-8">
                <div
                    x-data="{ active: 0, slides: @js($gallerySlides) }"
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div class="relative aspect-[4/3] bg-slate-100 sm:aspect-[16/10]">
                        <template x-for="(slide, idx) in slides" :key="idx">
                            <div
                                x-show="active === idx"
                                x-transition.opacity.duration.200ms
                                class="absolute inset-0"
                            >
                                <img
                                    x-show="slide.type === 'image'"
                                    :src="slide.src"
                                    alt="{{ $tour->title }}"
                                    loading="lazy"
                                    class="absolute inset-0 h-full w-full object-cover"
                                />
                                <iframe
                                    x-show="slide.type === 'youtube'"
                                    :src="slide.embedUrl"
                                    title="YouTube video"
                                    loading="lazy"
                                    class="absolute inset-0 h-full w-full border-0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        </template>

                        <button
                            type="button"
                            class="absolute left-3 top-1/2 -translate-y-1/2 rounded-xl bg-white/90 p-2 text-slate-900 shadow hover:bg-white"
                            @click="active = (active - 1 + slides.length) % slides.length"
                        >
                            <span class="sr-only">{{ __('ui.previous_image') }}</span>
                            <x-icon name="chevron-left" size="md" />
                        </button>
                        <button
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl bg-white/90 p-2 text-slate-900 shadow hover:bg-white"
                            @click="active = (active + 1) % slides.length"
                        >
                            <span class="sr-only">{{ __('ui.next_image') }}</span>
                            <x-icon name="chevron-right" size="md" />
                        </button>
                    </div>

                    <div class="grid grid-cols-4 gap-2 p-3 sm:grid-cols-6 sm:p-4">
                        <template x-for="(slide, idx) in slides" :key="'thumb-'+idx">
                            <button
                                type="button"
                                class="relative overflow-hidden rounded-xl border transition"
                                :class="active === idx ? 'border-slate-900' : 'border-slate-200 hover:border-slate-300'"
                                @click="active = idx"
                            >
                                <img
                                    :src="slide.posterUrl || slide.src"
                                    alt=""
                                    loading="lazy"
                                    class="h-14 w-full object-cover sm:h-20"
                                />
                                <span
                                    x-show="slide.type === 'youtube'"
                                    class="pointer-events-none absolute inset-0 flex items-center justify-center"
                                    aria-hidden="true"
                                >
                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-slate-900 shadow-md ring-1 ring-slate-200/80 sm:h-9 sm:w-9">
                                        <x-icon name="play" size="sm" class="ml-0.5 text-slate-900" />
                                    </span>
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:mt-10 sm:p-7">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">{{ __('tour.overview') }}</h2>
                    <div class="prose prose-slate mt-4 max-w-none text-sm sm:text-base">
                        {!! nl2br(e((string) $tour->description)) !!}
                    </div>
                </div>

                @php
                    $includedItems = \App\Models\Tour::labeledListItems($tour->includedItems(), 'services');
                    $excludedItems = \App\Models\Tour::labeledListItems($tour->excludedItems(), 'amenities');
                @endphp
                @if($includedItems !== [] || $excludedItems !== [])
                    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:mt-10 sm:p-7">
                        <div class="grid gap-8 sm:grid-cols-2">
                            @if($includedItems !== [])
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">{{ __('tour.included') }}</h2>
                                    <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                                        @foreach($includedItems as $label)
                                            <li class="flex gap-2">
                                                <span class="text-emerald-600" aria-hidden="true">✓</span>
                                                <span>{{ $label }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if($excludedItems !== [])
                                <div>
                                    <h2 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">{{ __('tour.excluded') }}</h2>
                                    <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                                        @foreach($excludedItems as $label)
                                            <li class="flex gap-2">
                                                <span class="text-slate-400" aria-hidden="true">×</span>
                                                <span>{{ $label }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:mt-10 sm:p-7">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">{{ __('tour.itinerary') }}</h2>
                    <div class="mt-5 grid gap-4">
                        @forelse($tour->itineraries as $itinerary)
                            <div class="rounded-2xl border border-slate-200 p-4 sm:p-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="text-sm font-semibold text-slate-900">{{ __('tour.day_title', ['day' => $itinerary->day, 'title' => $itinerary->title]) }}</div>
                                </div>
                                @if(filled($itinerary->description))
                                    <p class="mt-2 text-sm leading-7 text-slate-600">
                                        {{ $itinerary->description }}
                                    </p>
                                @endif
                                @php($schedule = is_array($itinerary->schedule) ? array_filter($itinerary->schedule, 'is_array') : [])
                                @if(! empty($schedule))
                                    <ol class="mt-4 space-y-3 border-l-2 border-slate-100 pl-4">
                                        @foreach($schedule as $slot)
                                            <li class="relative">
                                                <span class="absolute -left-[1.3rem] top-1.5 h-2.5 w-2.5 rounded-full border-2 border-white bg-slate-300 ring-1 ring-slate-200" aria-hidden="true"></span>
                                                <div class="flex flex-col gap-0.5 sm:flex-row sm:items-baseline sm:gap-3">
                                                    @if(filled($slot['time'] ?? null))
                                                        <span class="shrink-0 text-xs font-semibold tabular-nums text-slate-900">{{ $slot['time'] }}</span>
                                                    @endif
                                                    <div class="min-w-0">
                                                        @if(filled($slot['title'] ?? null))
                                                            <div class="text-sm font-medium text-slate-800">{{ $slot['title'] }}</div>
                                                        @endif
                                                        @if(filled($slot['description'] ?? null))
                                                            <p class="mt-0.5 text-sm leading-6 text-slate-600">{{ $slot['description'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                                {{ __('tour.itinerary_empty') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:mt-10 sm:p-7">
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">{{ __('tour.faq') }}</h2>
                    <div class="mt-4 grid gap-4">
                        @foreach([
                            ['q' => __('tour.faq.q1'), 'a' => __('tour.faq.a1')],
                            ['q' => __('tour.faq.q2'), 'a' => __('tour.faq.a2')],
                            ['q' => __('tour.faq.q3'), 'a' => __('tour.faq.a3')],
                        ] as $faq)
                            <details class="rounded-xl border border-slate-200 p-4">
                                <summary class="cursor-pointer text-sm font-semibold text-slate-900">{{ $faq['q'] }}</summary>
                                <p class="mt-2 text-sm leading-7 text-slate-600">{{ $faq['a'] }}</p>
                            </details>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 sm:mt-12">
                    <div class="flex items-end justify-between gap-6">
                        <h2 class="text-lg font-semibold tracking-tight text-slate-900 sm:text-xl">{{ __('ui.related_tours') }}</h2>
                        <a href="{{ route('tours.index', ['destination' => $tour->destination?->slug]) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                            {{ __('More in destination', ['destination' => $tour->destination?->localizedName() ?? '']) }}
                        </a>
                    </div>
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-2">
                        @foreach($relatedTours as $vm)
                            <x-tour-card :vm="$vm" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/95 p-3 shadow-[0_-8px_30px_rgba(15,23,42,0.08)] backdrop-blur lg:hidden">
        <a
            href="#tour-booking"
            class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white animate-book-now-blink"
        >
            {{ __('ui.book_now') }}
            <span class="font-semibold">· {{ $tour->formattedPrice() }}</span>
        </a>
    </div>
@endsection
