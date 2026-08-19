@props([
    // Input name for the selected ids, e.g. "featured_tiles[0][tour_ids]".
    'field',
    /** @var list<array{id: int, title: string, destination: string, thumbnail: string, price: string, is_active: bool}> $selected */
    'selected' => [],
    'max' => 8,
])

@php
    $selected = array_values($selected);
    $max = (int) $max;
@endphp

<div
    x-data="tourPicker({
        selected: @js($selected),
        max: {{ $max }},
        pickerUrl: @js(route('admin.tours.picker')),
    })"
    @keydown.escape.window="if (openModal) { openModal = false }"
>
    {{-- Selected ids travel with the settings form; order = display order. --}}
    <template x-for="item in items" :key="item.id">
        <input type="hidden" name="{{ $field }}[]" :value="item.id" />
    </template>

    <div class="flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-900/5 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
            <span x-text="items.length"></span> {{ __('admin.settings.featured_tiles.tour_word') }}
        </span>
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
            @click="open()"
            :disabled="atMax"
        >
            <x-icon name="add" size="sm" />
            {{ __('admin.settings.featured_tiles.tour_add') }}
        </button>
        <span class="text-[11px] text-slate-500" x-show="atMax">{{ __('admin.settings.featured_tiles.tour_max_reached', ['max' => $max]) }}</span>
    </div>

    {{-- A card may hold many tours: keep the settings page scannable. --}}
    <div class="mt-2 max-h-[22rem] space-y-2 overflow-y-auto pr-1" x-show="items.length > 0">
        <template x-for="(item, idx) in items" :key="item.id">
            <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white p-2 shadow-sm">
                <span class="w-4 shrink-0 text-right text-[11px] font-semibold text-slate-400" x-text="idx + 1"></span>
                <img :src="item.thumbnail" alt="" class="h-9 w-12 shrink-0 rounded-lg object-cover ring-1 ring-slate-200" loading="lazy" decoding="async" />
                <div class="min-w-0 flex-1">
                    <div class="truncate text-xs font-semibold text-slate-900" x-text="item.title"></div>
                    <div class="truncate text-[11px] text-slate-500">
                        <span x-text="item.destination"></span>
                        <span x-show="item.price"> · <span x-text="item.price"></span></span>
                        <span x-show="!item.is_active" class="font-semibold text-amber-700"> · {{ __('status.disabled') }}</span>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-1">
                    <button type="button" class="rounded-lg border border-slate-200 bg-white p-1 text-slate-500 shadow-sm hover:bg-slate-50 disabled:opacity-40" @click="move(idx, -1)" :disabled="idx === 0" :aria-label="'{{ __('admin.settings.featured_tiles.tour_move_up') }}'" title="{{ __('admin.settings.featured_tiles.tour_move_up') }}">
                        <x-icon name="chevron-down" size="sm" class="rotate-180" />
                    </button>
                    <button type="button" class="rounded-lg border border-slate-200 bg-white p-1 text-slate-500 shadow-sm hover:bg-slate-50 disabled:opacity-40" @click="move(idx, 1)" :disabled="idx === items.length - 1" :aria-label="'{{ __('admin.settings.featured_tiles.tour_move_down') }}'" title="{{ __('admin.settings.featured_tiles.tour_move_down') }}">
                        <x-icon name="chevron-down" size="sm" />
                    </button>
                    <button type="button" class="rounded-lg border border-slate-200 bg-white p-1 text-slate-500 shadow-sm hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600" @click="remove(item.id)" :aria-label="'{{ __('admin.settings.featured_tiles.tour_remove') }}'" title="{{ __('admin.settings.featured_tiles.tour_remove') }}">
                        <x-icon name="trash" size="sm" />
                    </button>
                </div>
            </div>
        </template>
    </div>

    <p class="mt-2 text-[11px] text-slate-500" x-show="items.length === 0">{{ __('admin.settings.featured_tiles.tour_none') }}</p>

    <x-admin.modal name="openModal" size="xl" :title="__('admin.settings.featured_tiles.picker_title')" :subtitle="__('admin.settings.featured_tiles.picker_subtitle')">
        <div>
            <label class="relative block">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <x-icon name="search" size="sm" />
                </span>
                <input
                    type="text"
                    x-ref="search"
                    x-model="q"
                    @input="searchDebounced()"
                    placeholder="{{ __('admin.settings.featured_tiles.picker_search') }}"
                    class="w-full rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                />
            </label>

            <div class="mt-4 max-h-[26rem] space-y-2 overflow-y-auto pr-1">
                <template x-for="tour in results" :key="tour.id">
                    <button
                        type="button"
                        class="flex w-full items-center gap-3 rounded-xl border p-2 text-left transition"
                        :class="isPicked(tour.id)
                            ? 'border-indigo-300 bg-indigo-50/70'
                            : (atMax ? 'cursor-not-allowed border-slate-200 bg-white opacity-50' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50')"
                        @click="toggle(tour)"
                        :aria-pressed="isPicked(tour.id)"
                    >
                        <span class="grid h-5 w-5 shrink-0 place-items-center rounded-md border" :class="isPicked(tour.id) ? 'border-indigo-500 bg-indigo-600 text-white' : 'border-slate-300 bg-white text-transparent'">
                            <x-icon name="check" size="sm" class="!h-3.5 !w-3.5" />
                        </span>
                        <img :src="tour.thumbnail" alt="" class="h-10 w-14 shrink-0 rounded-lg object-cover ring-1 ring-slate-200" loading="lazy" decoding="async" />
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-semibold text-slate-900" x-text="tour.title"></span>
                            <span class="block truncate text-xs text-slate-500">
                                <span x-text="tour.destination"></span>
                                <span x-show="tour.price"> · <span x-text="tour.price"></span></span>
                                <span x-show="!tour.is_active" class="font-semibold text-amber-700"> · {{ __('status.disabled') }}</span>
                            </span>
                        </span>
                    </button>
                </template>

                <p class="py-6 text-center text-sm text-slate-500" x-show="!loading && results.length === 0">{{ __('admin.settings.featured_tiles.picker_empty') }}</p>
                <p class="py-3 text-center text-xs text-slate-500" x-show="loading">{{ __('ui.loading') }}</p>

                <div class="pt-1" x-show="nextPageUrl && !loading">
                    <button type="button" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm hover:bg-slate-50" @click="loadMore()">
                        {{ __('admin.settings.featured_tiles.picker_load_more') }}
                    </button>
                </div>
            </div>
        </div>

        <x-slot:footer>
            <div class="flex w-full flex-wrap items-center justify-between gap-3">
                <span class="text-xs font-medium text-slate-600">
                    <span x-text="items.length"></span> {{ __('admin.settings.featured_tiles.tour_word') }}
                </span>
                <x-admin.button type="button" variant="primary" @click="openModal = false">
                    <x-icon name="check" size="sm" />
                    {{ __('admin.tour_form.catalog_modal_done') }}
                </x-admin.button>
            </div>
        </x-slot:footer>
    </x-admin.modal>
</div>
