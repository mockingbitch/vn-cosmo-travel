@inject('tourAttributes', 'App\Services\Admin\TourAttributeService')
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

    $serviceKeys = config('tour_catalog.services', []);
    $amenityKeys = config('tour_catalog.amenities', []);
    $labelsSvc = collect($serviceKeys)->mapWithKeys(fn ($k) => [$k => __('tour.catalog.service.'.$k)])->all();
    $labelsAmn = collect($amenityKeys)->mapWithKeys(fn ($k) => [$k => __('tour.catalog.amenity.'.$k)])->all();
    $cleanList = static fn ($raw): array => is_array($raw)
        ? array_values(array_filter(
            array_map(static fn ($v) => is_string($v) ? trim($v) : '', $raw),
            static fn (string $v) => $v !== ''
        ))
        : [];
    $oldSvc = old('services');
    $initialServices = is_array($oldSvc)
        ? $cleanList($oldSvc)
        : (isset($tour) && is_array($tour->services) ? $cleanList($tour->services) : []);
    $oldAmn = old('amenities');
    $initialAmenities = is_array($oldAmn)
        ? $cleanList($oldAmn)
        : (isset($tour) && is_array($tour->amenities) ? $cleanList($tour->amenities) : []);
    // Reusable custom items (admin-added, stored in DB) + any on this tour not yet persisted.
    $dbServiceOptions = $tourAttributes->optionsFor(\App\Models\TourAttribute::TYPE_SERVICE);
    $dbAmenityOptions = $tourAttributes->optionsFor(\App\Models\TourAttribute::TYPE_AMENITY);
    $tourCustomServices = array_values(array_diff($initialServices, $serviceKeys));
    $tourCustomAmenities = array_values(array_diff($initialAmenities, $amenityKeys));
    $initialCustomServices = array_values(array_unique(array_merge($dbServiceOptions, $tourCustomServices)));
    $initialCustomAmenities = array_values(array_unique(array_merge($dbAmenityOptions, $tourCustomAmenities)));

    $priceOld = old('price', $tour?->price);
    $priceInitial = ($priceOld !== null && $priceOld !== '') ? (int) $priceOld : null;
    $pricePlaceholderDigits = (int) preg_replace('/\D/', '', (string) __('placeholder.tour_price'));
    $pricePlaceholderFormatted = number_format(max(0, $pricePlaceholderDigits), 0, ',', '.');
    $currencyInitial = (string) old('currency', $tour?->currency ?? \App\Models\Tour::CURRENCY_VND);
    if (! array_key_exists($currencyInitial, \App\Models\Tour::CURRENCIES)) {
        $currencyInitial = \App\Models\Tour::CURRENCY_VND;
    }

    $thumbnailUrlField = old('thumbnail');
    if ($thumbnailUrlField === null) {
        $thumbnailUrlField = ($tour && $tour->thumbnail) ? $tour->thumbnail : '';
    }
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('destination') }}</label>
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

@unless(isset($tour) && $tour)
<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('status') }}</label>
    <select name="status" class="mt-1 w-full max-w-md rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
        <option value="{{ \App\Models\Tour::STATUS_ACTIVE }}" @selected(old('status', \App\Models\Tour::STATUS_ACTIVE) === \App\Models\Tour::STATUS_ACTIVE)>{{ __('status.active') }}</option>
        <option value="{{ \App\Models\Tour::STATUS_DISABLED }}" @selected(old('status', \App\Models\Tour::STATUS_ACTIVE) === \App\Models\Tour::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
    </select>
    @error('status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
@endunless

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('title') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.slug_auto') }}</p>
    <input name="title" value="{{ old('title', $tour?->title) }}" placeholder="{{ __('placeholder.tour_title') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
    @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('description') }}</label>
    <p class="mt-0.5 text-xs text-slate-500">{{ __('admin.tour_form.description_hint') }}</p>
    <textarea name="description" rows="6" placeholder="{{ __('placeholder.tour_description') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">{{ old('description', $tour?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div x-data="vndPriceInput(@js($priceInitial))">
    <label class="block text-sm font-medium text-slate-700">{{ __('price') }}</label>
    <input type="hidden" name="price" :value="raw === null || raw === '' ? '' : raw" required>
    <div class="mt-1 flex max-w-md gap-2">
        <input
            type="text"
            x-ref="vis"
            inputmode="numeric"
            autocomplete="off"
            placeholder="{{ $pricePlaceholderFormatted }}"
            class="min-w-0 flex-1 rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            @input="onInput($event)"
        />
        <select
            name="currency"
            aria-label="{{ __('ui.currency') }}"
            class="w-28 shrink-0 rounded-xl border border-slate-200 px-2 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        >
            @foreach(array_keys(\App\Models\Tour::CURRENCIES) as $code)
                <option value="{{ $code }}" @selected($currencyInitial === $code)>{{ $code }}</option>
            @endforeach
        </select>
    </div>
    @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    @error('currency')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div
    class="grid gap-3"
    x-data="{ thumbnailUrl: @js($thumbnailUrlField) }"
    @thumbnail-url-sync.window="thumbnailUrl = $event.detail?.url ?? ''"
>
    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('thumbnail') }}</label>
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
        customServices: @js($initialCustomServices),
        customAmenities: @js($initialCustomAmenities),
        dbServices: @js($dbServiceOptions),
        dbAmenities: @js($dbAmenityOptions),
        customServiceInput: '',
        customAmenityInput: '',
        state(type) {
            return type === 'service'
                ? { keys: this.serviceKeys, labels: this.labelsService, selected: 'selectedServices', custom: 'customServices', input: 'customServiceInput' }
                : { keys: this.amenityKeys, labels: this.labelsAmenity, selected: 'selectedAmenities', custom: 'customAmenities', input: 'customAmenityInput' };
        },
        labelOf(type, k) {
            return this.state(type).labels[k] ?? k;
        },
        // Every checkbox option in the picker: catalog keys first, then custom items.
        optionsOf(type) {
            const s = this.state(type);
            return [...s.keys, ...this[s.custom]];
        },
        addCustom(type) {
            const s = this.state(type);
            const value = (this[s.input] || '').trim();
            this[s.input] = '';
            if (value === '') return;
            // Skip if it already exists as a catalog label or a custom option.
            const exists = s.keys.some((k) => s.labels[k] === value) || this[s.custom].includes(value);
            if (! exists) this[s.custom].push(value);
            if (! this[s.selected].includes(value)) this[s.selected].push(value);
        },
        // Chip × / uncheck: drop from the selection only (custom stays an option).
        removeItem(type, item) {
            const s = this.state(type);
            this[s.selected] = this[s.selected].filter((k) => k !== item);
        },
        // Delete a custom option entirely (removes it from the picker and selection).
        deleteCustomOption(type, item) {
            const s = this.state(type);
            this[s.custom] = this[s.custom].filter((k) => k !== item);
            this[s.selected] = this[s.selected].filter((k) => k !== item);
        },
    }"
    @keydown.escape.window="openServicesModal = false; openAmenitiesModal = false"
>
    <div class="grid gap-4 lg:grid-cols-2">
        @php
            $fields = [
                ['type' => 'service', 'selected' => 'selectedServices', 'custom' => 'customServices', 'db' => 'dbServices', 'input' => 'customServiceInput', 'modal' => 'openServicesModal', 'name' => 'services', 'label' => __('ui.tour_services'), 'choose' => __('admin.tour_form.select_services')],
                ['type' => 'amenity', 'selected' => 'selectedAmenities', 'custom' => 'customAmenities', 'db' => 'dbAmenities', 'input' => 'customAmenityInput', 'modal' => 'openAmenitiesModal', 'name' => 'amenities', 'label' => __('ui.tour_amenities'), 'choose' => __('admin.tour_form.select_amenities')],
            ];
        @endphp
        @foreach($fields as $f)
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ $f['label'] }}</label>
                <div class="mt-2 min-h-[3.5rem] rounded-xl border border-slate-200 bg-slate-50/90 px-3 py-2">
                    <template x-if="{{ $f['selected'] }}.length === 0">
                        <p class="text-xs text-slate-500">{{ __('admin.tour_form.catalog_none') }}</p>
                    </template>
                    <ul class="flex flex-wrap gap-1.5" x-show="{{ $f['selected'] }}.length > 0">
                        <template x-for="k in {{ $f['selected'] }}" :key="'{{ $f['type'] }}-chip-'+k">
                            <li class="inline-flex items-center gap-1 rounded-full bg-white py-1 pl-2.5 pr-1.5 text-xs font-medium text-slate-800 ring-1 ring-slate-200">
                                <span x-text="labelOf('{{ $f['type'] }}', k)"></span>
                                <button type="button" class="leading-none text-slate-400 hover:text-rose-600" @click="removeItem('{{ $f['type'] }}', k)" aria-label="{{ __('admin.tour_form.remove_item') }}" title="{{ __('admin.tour_form.remove_item') }}">&times;</button>
                            </li>
                        </template>
                    </ul>
                </div>
                <button
                    type="button"
                    class="mt-2 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm hover:bg-slate-50"
                    @click="{{ $f['modal'] }} = true"
                >
                    <x-icon name="edit" size="sm" />
                    {{ $f['choose'] }}
                    <span x-show="{{ $f['selected'] }}.length > 0" x-text="{{ $f['selected'] }}.length" class="rounded-full bg-slate-100 px-1.5 text-xs font-semibold text-slate-600"></span>
                </button>
                <template x-for="k in {{ $f['selected'] }}" :key="'{{ $f['type'] }}-h-'+k">
                    <input type="hidden" name="{{ $f['name'] }}[]" :value="k" />
                </template>
                @error($f['name'])<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                @error($f['name'].'.*')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
        @endforeach
    </div>

    @foreach($fields as $f)
        <x-admin.modal :name="$f['modal']" :title="$f['type'] === 'service' ? __('admin.tour_form.modal_services_title') : __('admin.tour_form.modal_amenities_title')">
            <div class="max-h-[min(55vh,26rem)] space-y-2 overflow-y-auto pr-1">
                <template x-for="opt in optionsOf('{{ $f['type'] }}')" :key="'{{ $f['type'] }}-opt-'+opt">
                    <div class="flex items-center gap-2 rounded-xl border border-slate-100 px-3 py-2.5 hover:bg-slate-50">
                        <label class="flex flex-1 cursor-pointer items-center gap-3">
                            <input type="checkbox" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400" :value="opt" x-model="{{ $f['selected'] }}" />
                            <span class="text-sm leading-snug text-slate-800" x-text="labelOf('{{ $f['type'] }}', opt)"></span>
                        </label>
                        <button type="button" x-show="{{ $f['custom'] }}.includes(opt) &amp;&amp; ! {{ $f['db'] }}.includes(opt)" @click="deleteCustomOption('{{ $f['type'] }}', opt)" class="shrink-0 text-slate-400 hover:text-rose-600" aria-label="{{ __('admin.tour_form.remove_item') }}" title="{{ __('admin.tour_form.remove_item') }}">
                            <x-icon name="trash" size="sm" />
                        </button>
                    </div>
                </template>
            </div>
            <div class="mt-3 border-t border-slate-100 pt-3">
                <label class="mb-1.5 block text-xs font-medium text-slate-600">{{ __('admin.tour_form.custom_label') }}</label>
                <div class="flex gap-2">
                    <input
                        type="text"
                        maxlength="120"
                        class="min-w-0 flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm"
                        placeholder="{{ __('placeholder.custom_item') }}"
                        x-model="{{ $f['input'] }}"
                        @keydown.enter.prevent="addCustom('{{ $f['type'] }}')"
                    />
                    <button type="button" class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-50" @click="addCustom('{{ $f['type'] }}')">
                        <x-icon name="plus" size="sm" />
                        {{ __('admin.tour_form.custom_add') }}
                    </button>
                </div>
            </div>
            <div class="mt-4 flex justify-end border-t border-slate-100 pt-4">
                <button type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" @click="{{ $f['modal'] }} = false">
                    <x-icon name="check" size="sm" />
                    {{ __('admin.tour_form.catalog_modal_done') }}
                </button>
            </div>
        </x-admin.modal>
    @endforeach
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
                                        />
                                        <input
                                            type="text"
                                            class="min-w-0 flex-1 rounded-lg border border-slate-200 px-2 py-1.5 text-sm"
                                            placeholder="{{ __('placeholder.slot_title') }}"
                                            x-model="slot.title"
                                            :name="`itinerary[${idx}][schedule][${j}][title]`"
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
