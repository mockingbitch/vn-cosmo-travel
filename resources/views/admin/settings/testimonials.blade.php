@extends('admin.layouts.app')

@section('content')
    @php
        /** @var array<string, mixed> $testimonialsForm */
        $testimonialsForm = old('testimonials', $settings['content.testimonials'] ?? []);
        $testimonialsForm = is_array($testimonialsForm) ? $testimonialsForm : [];
        /** @var array<int, mixed> $itemsForm */
        $itemsForm = is_array($testimonialsForm['items'] ?? null) ? $testimonialsForm['items'] : [];
        $sceneUrlsInitial = [];
        for ($j = 0; $j < 3; $j++) {
            /** @var array<string, mixed> $row */
            $row = is_array($itemsForm[$j] ?? null) ? $itemsForm[$j] : [];
            $sceneUrlsInitial[] = (string) ($row['image_url'] ?? '');
        }
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Settings') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Manage website configuration') }}</p>
        </div>
        <a
            href="{{ route('home') }}#testimonials"
            target="_blank"
            rel="noopener"
            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            {{ __('Preview on site') }}
        </a>
    </div>

    <form
        method="POST"
        action="{{ route('admin.settings.testimonials.update') }}"
        class="mt-6"
        x-data="{ sceneUrls: @js($sceneUrlsInitial) }"
        @testimonial-scene-url-sync.window="
            const i = $event.detail?.index;
            const url = $event.detail?.url ?? '';
            if (i === 0 || i === 1 || i === 2) {
                sceneUrls = sceneUrls.map((u, k) => (k === i ? url : u));
            }
        "
    >
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('Home testimonials section') }}</div>
            <p class="mt-1 text-xs text-slate-500">{{ __('Home testimonials section help') }}</p>
            <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800 ring-1 ring-amber-200">{{ __('Home testimonials english only note') }}</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-1 sm:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Section title') }}</span>
                    <input
                        type="text"
                        name="testimonials[title]"
                        value="{{ $testimonialsForm['title'] ?? '' }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('testimonials.title')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="grid gap-1 sm:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Section subtitle') }}</span>
                    <textarea
                        name="testimonials[subtitle]"
                        rows="2"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >{{ $testimonialsForm['subtitle'] ?? '' }}</textarea>
                    @error('testimonials.subtitle')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>
            </div>

            @for($i = 0; $i < 3; $i++)
                @php
                    /** @var array<string, mixed> $it */
                    $it = is_array($itemsForm[$i] ?? null) ? $itemsForm[$i] : [];
                @endphp
                <div class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50/80 p-4">
                    <div class="text-xs font-semibold text-slate-700">{{ __('Testimonial card :number', ['number' => $i + 1]) }}</div>

                    <div class="mt-3 grid gap-1">
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.testimonial_image_url') }}</span>
                        <p class="text-xs text-slate-500">{{ __('admin.settings.testimonial_image_url_help') }}</p>
                        <div class="mt-1 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                            <input
                                type="text"
                                name="testimonials[items][{{ $i }}][image_url]"
                                x-model="sceneUrls[{{ $i }}]"
                                placeholder="{{ __('placeholder.thumbnail_url') }}"
                                autocomplete="off"
                                class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 font-mono text-xs text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            <x-admin.media-picker
                                pick-only="true"
                                :inline-toolbar="true"
                                :show-selected-previews="false"
                                sync-url-event="testimonial-scene-url-sync"
                                :sync-url-index="$i"
                                :show-open-library-link="false"
                            />
                        </div>
                        @error('testimonials.items.'.$i.'.image_url')
                            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                        @enderror
                    </div>

                    <label class="mt-3 grid gap-1">
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.testimonial_image_alt') }}</span>
                        <input
                            type="text"
                            name="testimonials[items][{{ $i }}][scene_alt]"
                            value="{{ $it['scene_alt'] ?? '' }}"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                        />
                        @error('testimonials.items.'.$i.'.scene_alt')
                            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                        @enderror
                    </label>

                    <label class="mt-3 grid gap-1">
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.testimonial_quote') }}</span>
                        <textarea
                            name="testimonials[items][{{ $i }}][quote]"
                            rows="4"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                        >{{ $it['quote'] ?? '' }}</textarea>
                        @error('testimonials.items.'.$i.'.quote')
                            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                        @enderror
                    </label>

                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <label class="grid gap-1">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.testimonial_author') }}</span>
                            <input
                                type="text"
                                name="testimonials[items][{{ $i }}][author]"
                                value="{{ $it['author'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            @error('testimonials.items.'.$i.'.author')
                                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                            @enderror
                        </label>

                        <label class="grid gap-1">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.testimonial_trip_meta') }}</span>
                            <input
                                type="text"
                                name="testimonials[items][{{ $i }}][meta]"
                                value="{{ $it['meta'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            @error('testimonials.items.'.$i.'.meta')
                                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                            @enderror
                        </label>
                    </div>
                </div>
            @endfor
        </div>

        <div class="mt-10 flex items-center justify-end gap-3 pt-2 sm:pt-3">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
            >
                {{ __('Save changes') }}
            </button>
        </div>
    </form>
@endsection
