@php
    /** @var \App\Models\Tour|null $tour */
    $normalizeSchedule = static function ($raw): array {
        if (! is_array($raw)) {
            return [];
        }

        return collect($raw)->map(function ($s) {
            if (! is_array($s)) {
                return ['time' => '', 'title' => '', 'description' => ''];
            }

            return [
                'time' => (string) ($s['time'] ?? ''),
                'title' => (string) ($s['title'] ?? ''),
                'description' => (string) ($s['description'] ?? ''),
            ];
        })->values()->all();
    };

    $itineraryOld = old('itinerary');
    if (is_array($itineraryOld) && $itineraryOld !== []) {
        $itineraryInitial = collect($itineraryOld)->map(function ($r) use ($normalizeSchedule) {
            if (! is_array($r)) {
                return ['title' => '', 'description' => '', 'schedule' => []];
            }

            return [
                'title' => (string) ($r['title'] ?? ''),
                'description' => (string) ($r['description'] ?? ''),
                'schedule' => $normalizeSchedule($r['schedule'] ?? null),
            ];
        })->values()->all();
    } elseif ($tour && $tour->relationLoaded('itineraries') && $tour->itineraries->isNotEmpty()) {
        $itineraryInitial = $tour->itineraries->map(fn ($i) => [
            'title' => $i->title,
            'description' => (string) ($i->description ?? ''),
            'schedule' => $normalizeSchedule($i->schedule),
        ])->values()->all();
    } else {
        $itineraryInitial = [['title' => '', 'description' => '', 'schedule' => []]];
    }

    $galleryRowKey = static function (): int {
        return (int) round(microtime(true) * 1000000) + random_int(0, 9999);
    };
    $galleryInitial = [];
    $galleryOld = old('gallery');
    if (is_array($galleryOld)) {
        foreach ($galleryOld as $u) {
            $galleryInitial[] = ['_k' => $galleryRowKey(), 'url' => trim((string) $u)];
        }
    } elseif (is_string($galleryOld) && trim($galleryOld) !== '') {
        foreach (preg_split('/\r\n|\r|\n/', $galleryOld) ?: [] as $line) {
            $line = trim((string) $line);
            if ($line !== '') {
                $galleryInitial[] = ['_k' => $galleryRowKey(), 'url' => $line];
            }
        }
    } elseif ($tour && $tour->relationLoaded('images') && $tour->images->isNotEmpty()) {
        foreach ($tour->images as $img) {
            $galleryInitial[] = ['_k' => $galleryRowKey(), 'url' => (string) $img->path];
        }
    }
    if ($galleryInitial === []) {
        $galleryInitial[] = ['_k' => $galleryRowKey(), 'url' => ''];
    }

    $cleanList = static fn ($raw): array => is_array($raw)
        ? array_values(array_filter(
            array_map(static fn ($v) => is_string($v) ? trim($v) : '', $raw),
            static fn (string $v) => $v !== ''
        ))
        : [];
    $oldIncluded = old('services');
    $initialIncluded = is_array($oldIncluded)
        ? $cleanList($oldIncluded)
        : (isset($tour) && is_array($tour->services) ? $cleanList($tour->services) : []);
    $oldExcluded = old('amenities');
    $initialExcluded = is_array($oldExcluded)
        ? $cleanList($oldExcluded)
        : (isset($tour) && is_array($tour->amenities) ? $cleanList($tour->amenities) : []);

    $oldDestinations = old('destination_ids');
    if (is_array($oldDestinations)) {
        $destinationIdsInitial = array_values(array_filter(array_map(
            static fn ($v) => (string) $v,
            $oldDestinations
        ), static fn (string $v): bool => $v !== ''));
    } elseif ($tour && $tour->relationLoaded('destinations') && $tour->destinations->isNotEmpty()) {
        $destinationIdsInitial = $tour->destinations->pluck('id')->map(fn ($id) => (string) $id)->all();
    } elseif ($tour && $tour->destination_id) {
        $destinationIdsInitial = [(string) $tour->destination_id];
    } else {
        $destinationIdsInitial = [];
    }
    if ($destinationIdsInitial === []) {
        $destinationIdsInitial = [''];
    }

    $destinationsByRegion = collect($destinations)->groupBy(
        static fn ($d) => filled($d->region) ? __('dest.region.'.$d->region) : __('ui.other')
    );

    $oldPrices = old('prices');
    $defaultPriceIndex = (int) old('default_price_index', 0);
    if (is_array($oldPrices) && $oldPrices !== []) {
        $priceRowsInitial = collect($oldPrices)
            ->filter(fn ($r) => is_array($r))
            ->map(fn ($r) => [
                'tour_price_type_id' => (string) ($r['tour_price_type_id'] ?? ''),
                'amount' => filled($r['amount'] ?? null)
                    ? (int) preg_replace('/\D/', '', (string) $r['amount'])
                    : null,
                'note' => (string) ($r['note'] ?? ''),
            ])
            ->values()
            ->all();
    } elseif ($tour && $tour->relationLoaded('prices') && $tour->prices->isNotEmpty()) {
        $priceRowsInitial = $tour->prices->map(fn ($p) => [
            'tour_price_type_id' => (string) $p->tour_price_type_id,
            'amount' => (int) $p->amount,
            'note' => (string) ($p->note ?? ''),
        ])->values()->all();
        $defaultIndexFromTour = $tour->prices->values()->search(fn ($p) => (bool) $p->is_default);
        $defaultPriceIndex = $defaultIndexFromTour === false ? 0 : (int) $defaultIndexFromTour;
    } else {
        $priceRowsInitial = [];
    }

    $thumbnailUrlField = old('thumbnail');
    if ($thumbnailUrlField === null) {
        $thumbnailUrlField = ($tour && $tour->thumbnail) ? $tour->thumbnail : '';
    }
@endphp

<div
    x-data="{
        rows: @js(array_map(static fn ($id, $i) => ['_k' => $i + 1, 'id' => $id], $destinationIdsInitial, array_keys($destinationIdsInitial))),
        nextKey: {{ count($destinationIdsInitial) + 1 }},
        addRow() {
            this.rows.push({ _k: this.nextKey++, id: '' });
        },
        removeRow(i) {
            this.rows.splice(i, 1);
            if (this.rows.length === 0) {
                this.rows.push({ _k: this.nextKey++, id: '' });
            }
        },
        /** One destination per row: the same place twice on a tour is meaningless. */
        isTaken(id, idx) {
            const value = String(id);
            return this.rows.some((row, k) => k !== idx && String(row.id) === value);
        },
    }"
>
    <label class="block text-sm font-medium text-slate-700">{{ __('destinations') }}</label>
    <x-admin.hint>{{ __('admin.tour_form.destinations_help') }}</x-admin.hint>

    <div class="mt-2 space-y-2">
        <template x-for="(row, idx) in rows" :key="row._k">
            <div class="flex items-center gap-2">
                <span
                    class="w-24 shrink-0 text-xs font-semibold"
                    :class="idx === 0 ? 'text-slate-700' : 'text-slate-500'"
                    x-text="idx === 0 ? '{{ __('admin.tour_form.destination_primary') }}' : ''"
                ></span>
                <select
                    class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    :name="`destination_ids[${idx}]`"
                    aria-label="{{ __('destination') }}"
                            :aria-label="`{{ __('destination') }} ${idx + 1}`"
                    x-model="row.id"
                >
                    <option value="">{{ __('admin.tour_form.destination_select') }}</option>
                    @foreach($destinationsByRegion as $regionLabel => $regionDestinations)
                        <optgroup label="{{ $regionLabel }}">
                            @foreach($regionDestinations as $d)
                                <option value="{{ $d->id }}" :disabled="isTaken({{ $d->id }}, idx)" title="{{ $d->name_vi }}">{{ $d->name_en }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white p-2 text-slate-500 shadow-sm hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"
                    @click="removeRow(idx)"
                    title="{{ __('admin.tour_form.destination_remove') }}"
                    :aria-label="'{{ __('admin.tour_form.destination_remove') }}'"
                >
                    <x-icon name="trash" size="sm" />
                </button>
            </div>
        </template>
    </div>

    <button
        type="button"
        class="mt-2 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
        @click="addRow"
    >
        <x-icon name="plus" size="sm" />
        {{ __('admin.tour_form.destination_add') }}
    </button>

    @error('destination_ids')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    @error('destination_ids.*')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

@unless(isset($tour) && $tour)
<div>
    <label for="tour-status" class="block text-sm font-medium text-slate-700">{{ __('status') }}</label>
    <select id="tour-status" name="status" class="mt-1 w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
        <option value="{{ \App\Models\Tour::STATUS_ACTIVE }}" @selected(old('status', \App\Models\Tour::STATUS_ACTIVE) === \App\Models\Tour::STATUS_ACTIVE)>{{ __('status.active') }}</option>
        <option value="{{ \App\Models\Tour::STATUS_DISABLED }}" @selected(old('status', \App\Models\Tour::STATUS_ACTIVE) === \App\Models\Tour::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
    </select>
    @error('status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
@endunless

<div>
    <label for="tour-title" class="block text-sm font-medium text-slate-700">{{ __('title') }}</label>
    <x-admin.hint id="tour-title-hint">{{ __('admin.tour_form.slug_auto') }}</x-admin.hint>
    <input id="tour-title" aria-describedby="tour-title-hint @error('title') tour-title-error @enderror" @error('title') aria-invalid="true" @enderror name="title" value="{{ old('title', $tour?->title) }}" placeholder="{{ __('placeholder.tour_title') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
    <x-admin.error field="title" id="tour-title-error" />
</div>

<div>
    <label for="tour-description" class="block text-sm font-medium text-slate-700">{{ __('description') }}</label>
    <x-admin.hint id="tour-description-hint">{{ __('admin.tour_form.description_hint') }}</x-admin.hint>
    <textarea id="tour-description" aria-describedby="tour-description-hint @error('description') tour-description-error @enderror" @error('description') aria-invalid="true" @enderror name="description" rows="6" placeholder="{{ __('placeholder.tour_description') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">{{ old('description', $tour?->description) }}</textarea>
    <x-admin.error field="description" id="tour-description-error" />
</div>

<x-admin.tour-price-rows
    :price-types="$priceTypes"
    :rows="$priceRowsInitial"
    :default-index="$defaultPriceIndex"
/>
<input type="hidden" name="currency" value="USD">

<div
    class="grid gap-3"
    x-data="{ thumbnailUrl: @js($thumbnailUrlField) }"
    @thumbnail-url-sync.window="thumbnailUrl = $event.detail?.url ?? ''"
>
    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('thumbnail') }}</label>
        <x-admin.hint>{{ __('admin.tour_form.thumbnail_help') }}</x-admin.hint>
    </div>
    <div>
        <label for="tour-thumbnail-url" class="block text-xs font-medium text-slate-600">{{ __('admin.tour_form.thumbnail_url_label') }}</label>
        <div class="mt-1 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
            <input
                id="tour-thumbnail-url"
                type="text"
                name="thumbnail"
                x-model="thumbnailUrl"
                placeholder="{{ __('placeholder.thumbnail_url') }}"
                autocomplete="off"
                class="min-w-0 flex-1 rounded-xl border border-slate-200 px-3 py-2 font-mono text-xs shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            />
            <x-admin.media-picker
                name="thumbnail_media_id"
                :value="old('thumbnail_media_id', $tour?->thumbnail_media_id)"
                :submit-when-empty="true"
                :inline-toolbar="true"
                sync-url-event="thumbnail-url-sync"
                :show-open-library-link="false"
            />
        </div>
    </div>
    @error('thumbnail')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    @error('thumbnail_media_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div
    class="grid gap-3"
    x-data="{
        rows: @js($galleryInitial),
        galleryRowKey() {
            return Math.round(performance.now() * 1000000) + Math.floor(Math.random() * 10000);
        },
        addGalleryRow() {
            this.rows.push({ _k: this.galleryRowKey(), url: '' });
        },
        removeGalleryRow(i) {
            this.rows.splice(i, 1);
            if (this.rows.length === 0) {
                this.rows.push({ _k: this.galleryRowKey(), url: '' });
            }
        },
        syncGalleryUrl(e) {
            const i = e.detail?.index;
            const u = e.detail?.url ?? '';
            if (typeof i === 'number' && this.rows[i]) {
                this.rows[i].url = u;
            }
        },
        youtubeVideoId(url) {
            if (! url || typeof url !== 'string') return null;
            const s = url.trim();
            let m = s.match(/(?:youtube\.com\/watch\?(?:[^#]*&)?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/);
            if (m) return m[1];
            m = s.match(/[?&]v=([a-zA-Z0-9_-]{11})/);
            return m ? m[1] : null;
        },
        galleryPreviewSrc(url) {
            const id = this.youtubeVideoId(url);
            if (id) return 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
            return (url || '').trim();
        },
    }"
    @gallery-url-sync.window="syncGalleryUrl($event)"
>
    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('admin.tour_form.gallery_section_title') }}</label>
        <x-admin.hint>{{ __('admin.tour_form.gallery_help') }}</x-admin.hint>
    </div>

    <div class="space-y-4">
        <template x-for="(row, idx) in rows" :key="row._k">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <label class="block text-xs font-medium text-slate-600">{{ __('admin.tour_form.gallery_row_label') }}</label>
                    <button
                        type="button"
                        class="shrink-0 text-xs font-semibold text-rose-600 hover:underline"
                        @click="removeGalleryRow(idx)"
                        x-show="rows.length > 1"
                    >
                        {{ __('admin.tour_form.remove_gallery_row') }}
                    </button>
                </div>
                <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-start sm:gap-4">
                    <div
                        class="shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm ring-1 ring-slate-200/80"
                        x-show="row.url && row.url.trim() !== ''"
                    >
                        <img
                            :src="galleryPreviewSrc(row.url)"
                            alt=""
                            class="h-20 w-28 max-w-full object-cover"
                            loading="lazy"
                            decoding="async"
                        />
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                        <input
                            type="text"
                            class="min-w-0 flex-1 rounded-xl border border-slate-200 px-3 py-2 font-mono text-xs shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            :name="`gallery[${idx}]`"
                            aria-label="{{ __('admin.tour_form.gallery_row_label') }}"
                            :aria-label="`{{ __('admin.tour_form.gallery_row_label') }} ${idx + 1}`"
                            x-model="row.url"
                            placeholder="{{ __('placeholder.gallery_item') }}"
                            autocomplete="off"
                        />
                        <x-admin.tour-gallery-row-picker />
                    </div>
                </div>
            </div>
        </template>
    </div>

    <button
        type="button"
        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm hover:bg-slate-50 sm:w-auto"
        @click="addGalleryRow"
    >
        {{ __('admin.tour_form.add_gallery_row') }}
    </button>

    @error('gallery')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    @error('gallery.*')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5">
    <div class="text-sm font-semibold text-slate-900">{{ __('admin.tour_form.included_excluded_section') }}</div>
    <x-admin.hint>{{ __('admin.tour_form.included_excluded_help') }}</x-admin.hint>

    <div class="mt-5 grid gap-6 lg:grid-cols-2">
        <x-admin.tour-list-editor
            name="services"
            :label="__('tour.included')"
            :help="__('admin.tour_form.included_help')"
            :items="$initialIncluded"
        />
        <x-admin.tour-list-editor
            name="amenities"
            :label="__('tour.excluded')"
            :help="__('admin.tour_form.excluded_help')"
            :items="$initialExcluded"
        />
    </div>
</div>

<div
    class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5"
    x-data="{
        rows: @js($itineraryInitial),
        addRow() {
            this.rows.push({ title: '', description: '', schedule: [] });
        },
        removeRow(i) {
            this.rows.splice(i, 1);
            if (this.rows.length === 0) {
                this.rows.push({ title: '', description: '', schedule: [] });
            }
        },
        addSlot(row) {
            if (! Array.isArray(row.schedule)) {
                row.schedule = [];
            }
            row.schedule.push({ time: '', title: '', description: '' });
        },
        removeSlot(row, j) {
            row.schedule.splice(j, 1);
        },
    }"
>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('ui.tour_itinerary') }}</label>
            <x-admin.hint>{{ __('admin.tour_form.itinerary_hint') }}</x-admin.hint>
            <p class="mt-1 text-xs font-medium text-slate-600">{{ __('admin.tour_form.duration_from_itinerary') }}</p>
        </div>
        <button
            type="button"
            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
            @click="addRow"
        >
            {{ __('admin.tour_form.add_day') }}
        </button>
    </div>

    <div class="mt-4 space-y-4">
        <template x-for="(row, idx) in rows" :key="idx">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('day') }} <span x-text="idx + 1"></span></span>
                    <button
                        type="button"
                        class="text-xs font-semibold text-rose-600 hover:underline"
                        @click="removeRow(idx)"
                    >
                        {{ __('admin.tour_form.remove_day') }}
                    </button>
                </div>
                <div class="mt-3 grid gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-600">{{ __('ui.day_title') }}</label>
                        <input
                            type="text"
                            class="mt-1 w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                            placeholder="{{ __('placeholder.itinerary_title') }}"
                            x-model="row.title"
                            :name="`itinerary[${idx}][title]`"
                            aria-label="{{ __('ui.day_title') }}"
                            :aria-label="`{{ __('ui.day_title') }} ${idx + 1}`"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600">{{ __('ui.day_description') }}</label>
                        <textarea
                            rows="2"
                            class="mt-1 w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                            placeholder="{{ __('placeholder.itinerary_description') }}"
                            x-model="row.description"
                            :name="`itinerary[${idx}][description]`"
                            aria-label="{{ __('ui.day_description') }}"
                            :aria-label="`{{ __('ui.day_description') }} ${idx + 1}`"
                        ></textarea>
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-slate-50/70 p-3">
                        <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700">{{ __('ui.itinerary_schedule') }}</label>
                                <p class="mt-0.5 text-[11px] text-slate-500">{{ __('admin.tour_form.schedule_hint') }}</p>
                            </div>
                            <button
                                type="button"
                                class="shrink-0 rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
                                @click="addSlot(row)"
                            >
                                {{ __('admin.tour_form.add_time_slot') }}
                            </button>
                        </div>

                        <div class="mt-3 space-y-2" x-show="Array.isArray(row.schedule) && row.schedule.length > 0">
                            <template x-for="(slot, j) in row.schedule" :key="j">
                                <div class="rounded-lg border border-slate-200 bg-white p-2.5">
                                    <div class="flex items-start gap-2">
                                        <input
                                            type="text"
                                            class="w-24 shrink-0 rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                                            placeholder="{{ __('placeholder.slot_time') }}"
                                            x-model="slot.time"
                                            :name="`itinerary[${idx}][schedule][${j}][time]`"
                                            aria-label="{{ __('ui.itinerary_schedule') }} — {{ __('placeholder.slot_time') }}"
                            :aria-label="`{{ __('placeholder.slot_time') }} ${j + 1}`"
                                        />
                                        <input
                                            type="text"
                                            class="min-w-0 flex-1 rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                                            placeholder="{{ __('placeholder.slot_title') }}"
                                            x-model="slot.title"
                                            :name="`itinerary[${idx}][schedule][${j}][title]`"
                                            aria-label="{{ __('ui.itinerary_schedule') }} — {{ __('placeholder.slot_title') }}"
                            :aria-label="`{{ __('placeholder.slot_title') }} ${j + 1}`"
                                        />
                                        <button
                                            type="button"
                                            class="shrink-0 px-1 py-1.5 text-xs font-semibold text-rose-600 hover:underline"
                                            @click="removeSlot(row, j)"
                                        >
                                            {{ __('admin.tour_form.remove_time_slot') }}
                                        </button>
                                    </div>
                                    <textarea
                                        rows="1"
                                        class="mt-2 w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                                        placeholder="{{ __('placeholder.slot_description') }}"
                                        x-model="slot.description"
                                        :name="`itinerary[${idx}][schedule][${j}][description]`"
                                        aria-label="{{ __('ui.itinerary_schedule') }} — {{ __('placeholder.slot_description') }}"
                            :aria-label="`{{ __('placeholder.slot_description') }} ${j + 1}`"
                                    ></textarea>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
    @error('itinerary')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    @error('itinerary.*')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
