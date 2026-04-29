@extends('layouts.app')

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="text-sm font-semibold text-slate-500">{{ $tour->destination?->localizedName() }}</div>
                    <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $tour->title }}</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                        {{ \Illuminate\Support\Str::limit(strip_tags((string) $tour->description), 220) }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900">
                        @if((int) $tour->duration === 1)
                            {{ __('1 day') }}
                        @else
                            {{ __(':count days', ['count' => $tour->duration]) }}
                        @endif
                    </div>
                    <div class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">
                        {{ number_format((int) $tour->price) }}₫ <span class="text-white/80 text-xs font-medium">{{ __('per person') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-12">
            <div class="lg:col-span-8">
                <div
                    x-data="{ active: 0, slides: @js($gallerySlides) }"
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div class="relative aspect-[16/10] bg-slate-100">
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
                            <span class="sr-only">{{ __('Previous image') }}</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button
                            type="button"
                            class="absolute right-3 top-1/2 -translate-y-1/2 rounded-xl bg-white/90 p-2 text-slate-900 shadow hover:bg-white"
                            @click="active = (active + 1) % slides.length"
                        >
                            <span class="sr-only">{{ __('Next image') }}</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-5 gap-2 p-4 sm:grid-cols-6">
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
                                    class="h-16 w-full object-cover sm:h-20"
                                />
                                <span
                                    x-show="slide.type === 'youtube'"
                                    class="pointer-events-none absolute inset-0 flex items-center justify-center"
                                    aria-hidden="true"
                                >
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-slate-900 shadow-md ring-1 ring-slate-200/80">
                                        <svg class="ml-0.5 h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M8 5v14l11-7z" />
                                        </svg>
                                    </span>
                                </span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <h2 class="text-xl font-semibold tracking-tight text-slate-900">{{ __('tour.overview') }}</h2>
                    <div class="prose prose-slate mt-4 max-w-none">
                        {!! nl2br(e((string) $tour->description)) !!}
                    </div>
                </div>

                @php
                    $serviceItems = is_array($tour->services) ? $tour->services : [];
                    $amenityItems = is_array($tour->amenities) ? $tour->amenities : [];
                @endphp
                @if($serviceItems !== [] || $amenityItems !== [])
                    <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">{{ __('tour.services_and_amenities') }}</h2>
                        <div class="mt-6 grid gap-8 sm:grid-cols-2">
                            @if($serviceItems !== [])
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">{{ __('tour.services') }}</h3>
                                    <ul class="mt-3 grid gap-2 text-sm text-slate-700">
                                        @foreach($serviceItems as $item)
                                            @php
                                                $allowedSvc = config('tour_catalog.services', []);
                                                $label = is_string($item) && in_array($item, $allowedSvc, true)
                                                    ? __('tour.catalog.service.'.$item)
                                                    : $item;
                                            @endphp
                                            <li class="flex gap-2"><span class="text-slate-400" aria-hidden="true">•</span> {{ $label }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if($amenityItems !== [])
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900">{{ __('tour.amenities') }}</h3>
                                    <ul class="mt-3 grid gap-2 text-sm text-slate-700">
                                        @foreach($amenityItems as $item)
                                            @php
                                                $allowedAmn = config('tour_catalog.amenities', []);
                                                $label = is_string($item) && in_array($item, $allowedAmn, true)
                                                    ? __('tour.catalog.amenity.'.$item)
                                                    : $item;
                                            @endphp
                                            <li class="flex gap-2"><span class="text-slate-400" aria-hidden="true">•</span> {{ $label }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <h2 class="text-xl font-semibold tracking-tight text-slate-900">{{ __('tour.itinerary') }}</h2>
                    <div class="mt-5 grid gap-4">
                        @forelse($tour->itineraries as $itinerary)
                            <div class="rounded-2xl border border-slate-200 p-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="text-sm font-semibold text-slate-900">{{ __('tour.day_title', ['day' => $itinerary->day, 'title' => $itinerary->title]) }}</div>
                                </div>
                                <p class="mt-2 text-sm leading-7 text-slate-600">
                                    {{ $itinerary->description }}
                                </p>
                            </div>
                        @empty
                            <div class="rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                                {{ __('tour.itinerary_empty') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <h2 class="text-xl font-semibold tracking-tight text-slate-900">{{ __('tour.faq') }}</h2>
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

                <div class="mt-12">
                    <div class="flex items-end justify-between gap-6">
                        <h2 class="text-xl font-semibold tracking-tight text-slate-900">{{ __('Related tours') }}</h2>
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

            <aside class="lg:col-span-4">
                <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ __('Book this tour') }}</div>
                            <div class="mt-1 text-xs text-slate-500">{{ __('We’ll contact you quickly to confirm details.') }}</div>
                        </div>
                        <div class="rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white">
                            {{ number_format((int) $tour->price) }}₫
                        </div>
                    </div>

                    @if(session('booking_success'))
                        <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {{ session('booking_success') }}
                        </div>
                    @endif

                    <form
                        class="mt-5 grid gap-4"
                        method="POST"
                        action="{{ route('tours.book', $tour) }}"
                        x-data="{ loading: false, message: null, errorMessage: null }"
                        @submit.prevent="
                            loading = true;
                            message = null;
                            errorMessage = null;
                            $el.querySelectorAll('p.text-rose-600').forEach(p => p.remove());
                            fetch($el.action, {
                                method: 'POST',
                                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                body: new FormData($el)
                            }).then(async (res) => {
                                if (res.status === 429) {
                                    const data = await res.json().catch(() => ({}));
                                    throw { rateLimited: true, message: (data && data.message) ? data.message : '{{ __('Too many requests. Please slow down and try again later.') }}' };
                                }
                                if (!res.ok) {
                                    const data = await res.json().catch(() => ({}));
                                    throw data;
                                }
                                return res.json();
                            }).then((data) => {
                                message = data.message;
                                $el.reset();
                            }).catch((err) => {
                                if (err && err.rateLimited) { errorMessage = err.message; return; }
                                $el.submit();
                            }).finally(() => loading = false);
                        "
                    >
                        @csrf

                        <x-input label="{{ __('Full name') }}" name="name" :placeholder="__('placeholder.name')" />
                        <x-input label="{{ __('Email') }}" name="email" type="email" :placeholder="__('placeholder.email')" />
                        <x-input label="{{ __('Phone') }}" name="phone" :placeholder="__('placeholder.phone')" />
                        <x-input label="{{ __('Travel date') }}" name="travel_date" type="date" />

                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">{{ __('People') }}</span>
                            <select
                                name="people_count"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" @selected((int) old('people_count', 2) === $i)>{{ $i }}</option>
                                @endfor
                                <option value="11" @selected((int) old('people_count', 2) === 11)>{{ __('booking.people_10_plus') }}</option>
                            </select>
                            @error('people_count')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-slate-700">{{ __('Note (optional)') }}</span>
                            <textarea
                                name="note"
                                rows="3"
                                placeholder="{{ __('placeholder.note') }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >{{ old('note') }}</textarea>
                            @error('note')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </label>

                        <x-button type="submit" variant="primary" class="w-full justify-center" x-bind:class="loading ? 'opacity-70 cursor-not-allowed' : ''">
                            <span x-show="!loading">{{ __('Request booking') }}</span>
                            <span x-show="loading">{{ __('Sending…') }}</span>
                        </x-button>

                        <template x-if="message">
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" x-text="message"></div>
                        </template>

                        <template x-if="errorMessage">
                            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" x-text="errorMessage"></div>
                        </template>

                        <p class="text-xs leading-5 text-slate-500">
                            {{ __('Booking consent') }}
                        </p>
                    </form>
                </div>
            </aside>
        </div>
    </section>
@endsection

