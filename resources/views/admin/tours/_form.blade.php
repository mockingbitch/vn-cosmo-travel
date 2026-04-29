@php
    /** @var \App\Models\Tour|null $tour */
    $itineraryOld = old('itinerary');
    if (is_array($itineraryOld) && $itineraryOld !== []) {
        $itineraryInitial = collect($itineraryOld)->map(function ($r) {
            if (! is_array($r)) {
                return ['title' => '', 'description' => ''];
            }

            return [
                'title' => (string) ($r['title'] ?? ''),
                'description' => (string) ($r['description'] ?? ''),
            ];
        })->values()->all();
    } elseif ($tour && $tour->relationLoaded('itineraries') && $tour->itineraries->isNotEmpty()) {
        $itineraryInitial = $tour->itineraries->map(fn ($i) => [
            'title' => $i->title,
            'description' => (string) ($i->description ?? ''),
        ])->values()->all();
    } else {
        $itineraryInitial = [['title' => '', 'description' => '']];
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

    $serviceKeys = config('tour_catalog.services', []);
    $amenityKeys = config('tour_catalog.amenities', []);
    $labelsSvc = collect($serviceKeys)->mapWithKeys(fn ($k) => [$k => __('tour.catalog.service.'.$k)])->all();
    $labelsAmn = collect($amenityKeys)->mapWithKeys(fn ($k) => [$k => __('tour.catalog.amenity.'.$k)])->all();
    $oldSvc = old('services');
    $initialServices = is_array($oldSvc)
        ? array_values(array_intersect($oldSvc, $serviceKeys))
        : (isset($tour) && is_array($tour->services)
            ? array_values(array_intersect($tour->services, $serviceKeys))
            : []);
    $oldAmn = old('amenities');
    $initialAmenities = is_array($oldAmn)
        ? array_values(array_intersect($oldAmn, $amenityKeys))
        : (isset($tour) && is_array($tour->amenities)
            ? array_values(array_intersect($tour->amenities, $amenityKeys))
            : []);

    $priceOld = old('price', $tour?->price);
    $priceInitial = ($priceOld !== null && $priceOld !== '') ? (int) $priceOld : null;
    $pricePlaceholderDigits = (int) preg_replace('/\D/', '', (string) __('placeholder.tour_price'));
    $pricePlaceholderFormatted = number_format(max(0, $pricePlaceholderDigits), 0, ',', '.');

    $thumbnailUrlField = old('thumbnail');
    if ($thumbnailUrlField === null) {
        $thumbnailUrlField = ($tour && $tour->thumbnail) ? $tour->thumbnail : '';
    }
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Destination') }}</label>
    <select name="destination_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
        @foreach($destinations as $d)
            <option
                value="{{ $d->id }}"
                title="{{ $d->name_vi }}"
                @selected(old('destination_id', $tour?->destination_id) == $d->id)
            >{{ $d->name_en }}</option>
        @endforeach
    </select>
    @error('destination_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Title') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.slug_auto') }}</p>
    <input name="title" value="{{ old('title', $tour?->title) }}" placeholder="{{ __('placeholder.tour_title') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
    @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Description') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.description_hint') }}</p>
    <textarea name="description" rows="6" placeholder="{{ __('placeholder.tour_description') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">{{ old('description', $tour?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div x-data="vndPriceInput(@js($priceInitial))">
    <label class="block text-sm font-medium text-slate-700">{{ __('Price (VND)') }}</label>
    <input type="hidden" name="price" :value="raw === null || raw === '' ? '' : raw" required>
    <input
        type="text"
        x-ref="vis"
        inputmode="numeric"
        autocomplete="off"
        placeholder="{{ $pricePlaceholderFormatted }}"
        class="mt-1 w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        @input="onInput($event)"
    />
    @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div
    class="grid gap-3"
    x-data="{ thumbnailUrl: @js($thumbnailUrlField) }"
    @thumbnail-url-sync.window="thumbnailUrl = $event.detail?.url ?? ''"
>
    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('Thumbnail') }}</label>
        <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.thumbnail_help') }}</p>
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-600">{{ __('admin.tour_form.thumbnail_url_label') }}</label>
        <div class="mt-1 flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
            <input
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
        <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.gallery_help') }}</p>
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

<div
    class="space-y-4"
    x-data="{
        openServicesModal: false,
        openAmenitiesModal: false,
        serviceKeys: @js($serviceKeys),
        amenityKeys: @js($amenityKeys),
        labelsService: @js($labelsSvc),
        labelsAmenity: @js($labelsAmn),
        selectedServices: @js($initialServices),
        selectedAmenities: @js($initialAmenities),
    }"
    @keydown.escape.window="openServicesModal = false; openAmenitiesModal = false"
>
    <div class="grid gap-4 lg:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('Tour services') }}</label>
            <div class="mt-2 min-h-[3.5rem] rounded-xl border border-slate-200 bg-slate-50/90 px-3 py-2">
                <template x-if="selectedServices.length === 0">
                    <p class="text-xs text-slate-500">{{ __('admin.tour_form.catalog_none') }}</p>
                </template>
                <ul class="flex flex-wrap gap-1.5" x-show="selectedServices.length > 0">
                    <template x-for="k in selectedServices" :key="'svc-chip-'+k">
                        <li class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-800 ring-1 ring-slate-200" x-text="labelsService[k]"></li>
                    </template>
                </ul>
            </div>
            <button
                type="button"
                class="mt-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm hover:bg-slate-50"
                @click="openServicesModal = true"
            >
                {{ __('admin.tour_form.select_services') }}
            </button>
            <template x-for="k in selectedServices" :key="'svc-h-'+k">
                <input type="hidden" name="services[]" :value="k" />
            </template>
            @error('services')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            @error('services.*')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('Tour amenities') }}</label>
            <div class="mt-2 min-h-[3.5rem] rounded-xl border border-slate-200 bg-slate-50/90 px-3 py-2">
                <template x-if="selectedAmenities.length === 0">
                    <p class="text-xs text-slate-500">{{ __('admin.tour_form.catalog_none') }}</p>
                </template>
                <ul class="flex flex-wrap gap-1.5" x-show="selectedAmenities.length > 0">
                    <template x-for="k in selectedAmenities" :key="'amn-chip-'+k">
                        <li class="rounded-full bg-white px-2.5 py-1 text-xs font-medium text-slate-800 ring-1 ring-slate-200" x-text="labelsAmenity[k]"></li>
                    </template>
                </ul>
            </div>
            <button
                type="button"
                class="mt-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm hover:bg-slate-50"
                @click="openAmenitiesModal = true"
            >
                {{ __('admin.tour_form.select_amenities') }}
            </button>
            <template x-for="k in selectedAmenities" :key="'amn-h-'+k">
                <input type="hidden" name="amenities[]" :value="k" />
            </template>
            @error('amenities')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            @error('amenities.*')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <x-admin.modal name="openServicesModal" :title="__('admin.tour_form.modal_services_title')">
        <div class="max-h-[min(60vh,28rem)] space-y-2 overflow-y-auto pr-1">
            <template x-for="key in serviceKeys" :key="key">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-100 px-3 py-2.5 hover:bg-slate-50">
                    <input type="checkbox" class="mt-0.5 rounded border-slate-300 text-slate-900 focus:ring-slate-400" :value="key" x-model="selectedServices" />
                    <span class="text-sm leading-snug text-slate-800" x-text="labelsService[key]"></span>
                </label>
            </template>
        </div>
        <div class="mt-4 flex justify-end border-t border-slate-100 pt-4">
            <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" @click="openServicesModal = false">
                {{ __('admin.tour_form.catalog_modal_done') }}
            </button>
        </div>
    </x-admin.modal>

    <x-admin.modal name="openAmenitiesModal" :title="__('admin.tour_form.modal_amenities_title')">
        <div class="max-h-[min(60vh,28rem)] space-y-2 overflow-y-auto pr-1">
            <template x-for="key in amenityKeys" :key="key">
                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-100 px-3 py-2.5 hover:bg-slate-50">
                    <input type="checkbox" class="mt-0.5 rounded border-slate-300 text-slate-900 focus:ring-slate-400" :value="key" x-model="selectedAmenities" />
                    <span class="text-sm leading-snug text-slate-800" x-text="labelsAmenity[key]"></span>
                </label>
            </template>
        </div>
        <div class="mt-4 flex justify-end border-t border-slate-100 pt-4">
            <button type="button" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" @click="openAmenitiesModal = false">
                {{ __('admin.tour_form.catalog_modal_done') }}
            </button>
        </div>
    </x-admin.modal>
</div>

<div
    class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5"
    x-data="{
        rows: @js($itineraryInitial),
        addRow() {
            this.rows.push({ title: '', description: '' });
        },
        removeRow(i) {
            this.rows.splice(i, 1);
            if (this.rows.length === 0) {
                this.rows.push({ title: '', description: '' });
            }
        },
    }"
>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <label class="block text-sm font-medium text-slate-700">{{ __('Tour itinerary') }}</label>
            <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.itinerary_hint') }}</p>
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
                    <span class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Day') }} <span x-text="idx + 1"></span></span>
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
                        <label class="block text-xs font-medium text-slate-600">{{ __('Day title') }}</label>
                        <input
                            type="text"
                            class="mt-1 w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                            placeholder="{{ __('placeholder.itinerary_title') }}"
                            x-model="row.title"
                            :name="`itinerary[${idx}][title]`"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600">{{ __('Day description') }}</label>
                        <textarea
                            rows="2"
                            class="mt-1 w-full rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                            placeholder="{{ __('placeholder.itinerary_description') }}"
                            x-model="row.description"
                            :name="`itinerary[${idx}][description]`"
                        ></textarea>
                    </div>
                </div>
            </div>
        </template>
    </div>
    @error('itinerary')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    @error('itinerary.*')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
