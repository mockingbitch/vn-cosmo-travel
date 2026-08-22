@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    @php
        /** @var list<array{eyebrow: string, title: string, chips: list<string>, description: string, destination_slug: string, image_url: string}> $tileDefaults */
        $tilesForm = old('featured_tiles', $settings['content.featured_tiles'] ?? []);
        $tilesForm = is_array($tilesForm) ? $tilesForm : [];

        $maxToursPerTile = \App\Services\FeaturedTilesService::MAX_TOURS_PER_TILE;

        $imageUrlsInitial = [];
        $selectedTours = [];
        foreach ($tileDefaults as $i => $default) {
            $row = is_array($tilesForm[$i] ?? null) ? $tilesForm[$i] : [];
            $imageUrlsInitial[] = (string) ($row['image_url'] ?? '');

            // Ids from the form (or the saved tile), hydrated with the payloads the
            // controller looked up, so the picker shows titles and not bare ids.
            $ids = is_array($row['tour_ids'] ?? null)
                ? array_values($row['tour_ids'])
                : ($tiles[$i]['tour_ids'] ?? []);
            $picked = [];
            foreach ($ids as $id) {
                $id = (int) $id;
                if ($id > 0 && isset($tourLookup[$id])) {
                    $picked[] = $tourLookup[$id];
                }
            }
            $selectedTours[] = $picked;
        }

        // Slot shapes come from FeaturedTilesService, so the labels here can never
        // drift from the real mosaic layout.
        /** @var list<array{span: string, wide: bool, label: string}> $tileShapes */
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.settings.page_title') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('admin.settings.page_subtitle') }}</p>
        </div>
        <a
            href="{{ route('home') }}"
            target="_blank"
            rel="noopener"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
        >
            <x-icon name="external-link" size="sm" />
            {{ __('ui.preview_on_site') }}
        </a>
    </div>

    <form
        method="POST"
        action="{{ route('admin.settings.featuredTiles.update') }}"
        class="mt-6"
        x-data="{ imageUrls: @js($imageUrlsInitial) }"
        @featured-tile-image-sync.window="
            const i = $event.detail?.index;
            const url = $event.detail?.url ?? '';
            if (typeof i === 'number') {
                imageUrls = imageUrls.map((u, k) => (k === i ? url : u));
            }
        "
    >
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('admin.settings.featured_tiles.section') }}</div>
            <x-admin.hint>{{ __('admin.settings.featured_tiles.help') }}</x-admin.hint>
            <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800 ring-1 ring-amber-200">{{ __('admin.settings.featured_tiles.fallback_note') }}</p>

            @foreach($tileDefaults as $i => $default)
                @php
                    /** @var array<string, mixed> $row */
                    $row = is_array($tilesForm[$i] ?? null) ? $tilesForm[$i] : [];
                    $defaultChips = implode(', ', $default['chips']);
                @endphp
                <div class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50/80 p-4">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.tile', ['number' => $i + 1]) }}</span>
                        <span class="rounded-full bg-slate-200/70 px-2 py-0.5 text-[11px] font-medium text-slate-700">{{ __('admin.settings.featured_tiles.slot_'.($tileShapes[$i]['label'] ?? 'small')) }}</span>
                        <span class="text-[11px] text-slate-500">{{ __('admin.settings.featured_tiles.default_is', ['value' => $default['title']]) }}</span>
                        <span @class([
                            'ms-auto rounded-full px-2 py-0.5 text-[11px] font-semibold',
                            'bg-indigo-50 text-indigo-700' => $selectedTours[$i] !== [],
                            'bg-slate-200/70 text-slate-600' => $selectedTours[$i] === [],
                        ])>{{ __('admin.settings.featured_tiles.tour_badge', ['count' => count($selectedTours[$i])]) }}</span>
                    </div>

                    <div class="mt-3 grid gap-3 sm:grid-cols-3">
                        <label class="grid gap-1">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.eyebrow') }}</span>
                            <input
                                type="text"
                                name="featured_tiles[{{ $i }}][eyebrow]"
                                value="{{ $row['eyebrow'] ?? '' }}"
                                placeholder="{{ $default['eyebrow'] }}"
                                maxlength="60"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            @error('featured_tiles.'.$i.'.eyebrow')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror
                        </label>

                        <label class="grid gap-1 sm:col-span-2">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.title_field') }}</span>
                            <input
                                type="text"
                                name="featured_tiles[{{ $i }}][title]"
                                value="{{ $row['title'] ?? '' }}"
                                placeholder="{{ $default['title'] }}"
                                maxlength="120"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            @error('featured_tiles.'.$i.'.title')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror
                        </label>

                        <label class="grid gap-1 sm:col-span-3">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.chips') }}</span>
                            <x-admin.hint>{{ __('admin.settings.featured_tiles.chips_help', ['max' => \App\Services\FeaturedTilesService::MAX_CHIPS]) }}</x-admin.hint>
                            <input
                                type="text"
                                name="featured_tiles[{{ $i }}][chips]"
                                value="{{ $row['chips'] ?? '' }}"
                                placeholder="{{ $defaultChips }}"
                                maxlength="400"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            @error('featured_tiles.'.$i.'.chips')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror
                        </label>

                        <label class="grid gap-1 sm:col-span-3">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.description') }}</span>
                            @unless($tileShapes[$i]['wide'] ?? false)
                                <x-admin.hint>{{ __('admin.settings.featured_tiles.description_hidden_help') }}</x-admin.hint>
                            @endunless
                            <textarea
                                name="featured_tiles[{{ $i }}][description]"
                                rows="2"
                                maxlength="500"
                                placeholder="{{ $default['description'] }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >{{ $row['description'] ?? '' }}</textarea>
                            @error('featured_tiles.'.$i.'.description')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror
                        </label>

                        <label class="grid gap-1">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.cta_label') }}</span>
                            <input
                                type="text"
                                name="featured_tiles[{{ $i }}][cta_label]"
                                value="{{ $row['cta_label'] ?? '' }}"
                                placeholder="{{ __('home.featured_tiles.cta') }}"
                                maxlength="60"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            @error('featured_tiles.'.$i.'.cta_label')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror
                        </label>

                        <div class="grid gap-1 sm:col-span-2">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.url') }}</span>
                            <x-admin.hint>{{ __('admin.settings.featured_tiles.url_help') }}</x-admin.hint>
                            <div class="flex flex-wrap items-center gap-2">
                                <code class="min-w-0 flex-1 truncate rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 font-mono text-xs text-slate-600">{{ route('featured', ['p' => $tiles[$i]['slug']], false) }}</code>
                                <a
                                    href="{{ route('featured', ['p' => $tiles[$i]['slug']]) }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
                                >
                                    <x-icon name="external-link" size="sm" />
                                    {{ __('ui.preview_on_site') }}
                                </a>
                            </div>
                        </div>

                        <div class="grid gap-1 sm:col-span-3">
                            <label for="tile-image-{{ $i }}" class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.image_url') }}</label>
                            <x-admin.hint :id="'tile-image-'.$i.'-hint'">{{ __('admin.settings.featured_tiles.image_url_help') }}</x-admin.hint>
                            <div class="mt-1 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                                <input
                                    id="tile-image-{{ $i }}"
                                    type="text"
                                    aria-describedby="tile-image-{{ $i }}-hint"
                                    name="featured_tiles[{{ $i }}][image_url]"
                                    x-model="imageUrls[{{ $i }}]"
                                    placeholder="{{ __('placeholder.thumbnail_url') }}"
                                    autocomplete="off"
                                    class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 font-mono text-xs text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                />
                                <x-admin.media-picker
                                    pick-only="true"
                                    :inline-toolbar="true"
                                    :show-selected-previews="false"
                                    sync-url-event="featured-tile-image-sync"
                                    :sync-url-index="$i"
                                    :show-open-library-link="false"
                                />
                            </div>
                            @error('featured_tiles.'.$i.'.image_url')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror

                            <div class="mt-2 overflow-hidden rounded-xl border border-slate-200 bg-white" x-show="(imageUrls[{{ $i }}] || '').trim() !== ''">
                                <img :src="imageUrls[{{ $i }}]" alt="" class="h-24 w-full object-cover sm:h-28" loading="lazy" decoding="async" />
                            </div>
                        </div>

                        <div class="grid gap-1 sm:col-span-3">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.featured_tiles.tours') }}</span>
                            <x-admin.hint>{{ __('admin.settings.featured_tiles.tours_help', ['max' => $maxToursPerTile]) }}</x-admin.hint>

                            @if($tourCount === 0)
                                <div class="mt-1 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">
                                    {{ __('admin.settings.featured_tiles.tours_empty') }}
                                </div>
                            @else
                                <div class="mt-1">
                                    <x-admin.tour-picker
                                        :field="'featured_tiles['.$i.'][tour_ids]'"
                                        :selected="$selectedTours[$i]"
                                        :max="$maxToursPerTile"
                                    />
                                </div>
                            @endif

                            @error('featured_tiles.'.$i.'.tour_ids')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror
                            @error('featured_tiles.'.$i.'.tour_ids.*')<div class="text-xs font-medium text-rose-700">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <x-admin.form-actions
            submit-label="{{ __('admin.settings.save_changes') }}"
            submit-icon="save"
        />
    </form>
@endsection
