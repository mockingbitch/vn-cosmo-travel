@php
    // A page may render several pickers (the tour form has two); the search
    // panel id must be unique or aria-controls points at the wrong panel.
    $searchPanelId = 'media-picker-search-'.substr(\Illuminate\Support\Str::uuid()->toString(), 0, 8);
@endphp

@props([
    'inlineToolbar' => false,
    'showOpenLibraryLink' => true,
    'showSelectedPreviews' => true,
])

@php
    $inlineToolbar = (bool) $inlineToolbar;
    $showOpenLibraryLink = (bool) $showOpenLibraryLink;
    $showSelectedPreviews = (bool) $showSelectedPreviews;
@endphp

<div @class(['flex flex-wrap items-center', $inlineToolbar ? 'gap-2' : 'gap-3'])>
    @if(! $inlineToolbar)
        <template x-if="selected.length === 0">
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600">
                {{ __('No media selected') }}
            </div>
        </template>
    @endif

    @if($showSelectedPreviews)
        <template x-for="m in selected" :key="m.id">
            <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <img :src="m.url" class="h-20 w-28 object-cover" alt="" />
                <button
                    type="button"
                    class="absolute right-2 top-2 rounded-lg bg-white/90 px-2 py-1 text-xs font-semibold text-slate-700 shadow-sm hover:bg-white"
                    @click="remove(m.id)"
                >
                    {{ __('Remove') }}
                </button>
            </div>
        </template>
    @else
        <button
            type="button"
            class="shrink-0 text-xs font-semibold text-slate-600 underline underline-offset-4 hover:text-slate-900"
            x-show="pickOnly && selectedIds.length"
            x-cloak
            @click="remove(selectedIds[0])"
        >
            {{ __('Remove') }}
        </button>
    @endif

    <x-admin.button
        type="button"
        variant="secondary"
        @class(['shrink-0 whitespace-nowrap' => $inlineToolbar])
        @click="open()"
    >
        <x-icon name="folder" size="sm" />
        {{ __('Choose from library') }}
    </x-admin.button>
    @if($showOpenLibraryLink)
        <a class="text-xs font-semibold text-slate-600 hover:text-slate-900 underline underline-offset-4" href="{{ route('admin.media.index') }}" target="_blank" rel="noopener noreferrer">{{ __('Open media library') }}</a>
    @endif
</div>

<x-admin.modal
    name="openModal"
    size="xl"
    :title="__('Media library')"
    :subtitle="__('admin.media.picker_subtitle')"
>
    <x-slot:actions>
        <button
            type="button"
            class="relative rounded-lg p-1.5 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2"
            :class="searchOpen ? 'bg-slate-900 text-white hover:bg-slate-800' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900'"
            @click="toggleSearch()"
            :aria-expanded="searchOpen.toString()"
            aria-controls="{{ $searchPanelId }}"
            :aria-label="q ? '{{ __('admin.media.search_active') }}' : '{{ __('admin.media.search_label') }}'"
            :title="q ? '{{ __('admin.media.search_active') }}' : '{{ __('admin.media.search_label') }}'"
        >
            <x-icon name="search" size="md" />
            {{-- A hidden panel must never hide an active filter. --}}
            <span
                class="absolute right-1 top-1 h-2 w-2 rounded-full bg-indigo-500 ring-2 ring-white"
                x-show="q && ! searchOpen"
                x-cloak
                aria-hidden="true"
            ></span>
        </button>
    </x-slot:actions>
    <div x-init="load()" @keydown.window="modalKeydown($event)">
        {{-- Search panel: hidden until the header icon is pressed. Negative
             margins escape body padding so border-b spans full width. --}}
        <div
            id="{{ $searchPanelId }}"
            x-show="searchOpen"
            x-cloak
            x-transition.opacity.duration.150ms
            class="sticky top-0 z-10 -mx-6 -mt-5 mb-4 border-b border-slate-200 bg-white/95 px-6 pb-3 pt-5 backdrop-blur"
        >
            <div class="flex items-center gap-2">
                <label class="relative block min-w-0 flex-1">
                    <span class="sr-only">{{ __('admin.media.search_label') }}</span>
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                        <x-icon name="search" size="sm" />
                    </span>
                    <input
                        type="search"
                        placeholder="{{ __('placeholder.media_filename') }}"
                        autocomplete="off"
                        class="block w-full rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-500 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                        x-model="q"
                        @input.debounce.300ms="reload()"
                        @search="q = ($event.target && $event.target.value) ? $event.target.value : ''; reload()"
                        @keydown.escape.stop="closeSearch()"
                        x-ref="search"
                    />
                </label>

                <button
                    type="button"
                    class="shrink-0 rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400"
                    x-show="q"
                    x-cloak
                    @click="clearSearch()"
                >{{ __('ui.clear_filter') }}</button>

                <button
                    type="button"
                    class="shrink-0 rounded-lg p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400"
                    @click="closeSearch()"
                    aria-label="{{ __('admin.media.search_hide') }}"
                    title="{{ __('admin.media.search_hide') }}"
                >
                    <x-icon name="close" size="sm" />
                </button>
            </div>
        </div>

        {{-- First load / re-search --}}
        <div class="grid place-items-center py-16" x-show="loading && items.length === 0" x-cloak>
            <span class="h-6 w-6 animate-spin rounded-full border-2 border-slate-300 border-t-slate-900" aria-hidden="true"></span>
            <p class="mt-3 text-xs font-medium text-slate-600" role="status">{{ __('ui.loading') }}</p>
        </div>

        {{-- Body: grid of media cards or empty state --}}
        <template x-if="items.length > 0">
            <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <template x-for="m in items" :key="m.id">
                    <button
                        type="button"
                        class="group relative overflow-hidden rounded-2xl border bg-white text-left shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-200/60 hover:-translate-y-0.5 hover:shadow-md"
                        :class="selectedIds.includes(m.id) ? 'border-indigo-500 ring-2 ring-indigo-500/40' : 'border-slate-200'"
                        @click="toggle(m)"
                        @keydown.enter.prevent="toggle(m)"
                        :aria-pressed="selectedIds.includes(m.id).toString()"
                        aria-label="{{ __('admin.media.select_media') }}"
                        :aria-label="m.file_name"
                    >
                        <div class="aspect-[4/3] bg-slate-100">
                            <img :src="m.url" class="h-full w-full object-cover transition duration-200 group-hover:scale-[1.02]" alt="" loading="lazy" />
                        </div>
                        <div class="p-3">
                            <div class="truncate text-sm font-semibold text-slate-900" x-text="m.file_name"></div>
                        </div>

                        {{-- Selected check badge --}}
                        <div
                            class="pointer-events-none absolute right-2 top-2 grid h-7 w-7 place-items-center rounded-full transition"
                            :class="selectedIds.includes(m.id) ? 'bg-indigo-600 text-white shadow-md' : 'bg-white/90 text-transparent ring-1 ring-slate-200 opacity-0 group-hover:opacity-100'"
                        >
                            <x-icon name="check" size="sm" class="text-current" />
                        </div>
                    </button>
                </template>
            </div>
        </template>

        {{-- Empty state --}}
        <template x-if="items.length === 0 && ! loading">
            <div class="grid place-items-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-6 py-16 text-center">
                <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-slate-500 shadow-sm ring-1 ring-slate-200">
                    <x-icon name="photo" size="lg" />
                </div>
                <div class="mt-3 text-sm font-semibold text-slate-900">
                    <span x-show="q">{{ __('No media match your search.') }}</span>
                    <span x-show="!q" x-cloak>{{ __('Your library is empty.') }}</span>
                </div>
                <p class="mt-1 max-w-sm text-xs text-slate-500">
                    {{ __('admin.media.upload_images_cta') }}
                    <a class="font-semibold text-slate-700 underline underline-offset-2 hover:text-slate-900" href="{{ route('admin.media.index') }}" target="_blank" rel="noopener noreferrer">{{ __('Media library') }}</a>.
                </p>
            </div>
        </template>

        {{-- Infinite scroll: the sentinel sits below the grid; reaching it loads
             the next page. The button is the keyboard/no-IntersectionObserver
             path to the same call. --}}
        <div x-show="nextPageUrl" x-cloak class="pt-4">
            <div x-init="watchSentinel($el)" aria-hidden="true" class="h-px w-full"></div>

            <div class="flex items-center justify-center gap-3 py-2" role="status" aria-live="polite">
                <template x-if="loadingMore">
                    <span class="inline-flex items-center gap-2 text-xs font-medium text-slate-600">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-slate-300 border-t-slate-900" aria-hidden="true"></span>
                        {{ __('admin.media.loading_more') }}
                    </span>
                </template>
                <template x-if="! loadingMore">
                    <button
                        type="button"
                        class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2"
                        @click="loadMore()"
                    >
                        {{ __('admin.media.load_more') }}
                    </button>
                </template>
            </div>
        </div>

        <p
            class="pt-4 text-center text-xs text-slate-500"
            x-show="! nextPageUrl && items.length > 0 && ! loading"
            x-cloak
        >{{ __('admin.media.all_loaded') }}</p>
    </div>

    {{-- Sticky footer: count chip + actions --}}
    <x-slot:footer>
        <div class="flex items-center justify-between gap-3">
            <div class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                <span class="grid h-5 w-5 place-items-center rounded-full bg-indigo-600 text-[10px] font-bold text-white" x-text="selectedIds.length"></span>
                {{ __('selected') }}
            </div>
            <div class="flex items-center gap-2">
                <x-admin.button type="button" variant="ghost" @click="openModal = false">
                    <x-icon name="close" size="sm" />
                    {{ __('Close') }}
                </x-admin.button>
                <x-admin.button
                    type="button"
                    variant="primary"
                    x-bind:disabled="selectedIds.length === 0"
                    @click="confirm()"
                >
                    <x-icon name="check" size="sm" />
                    {{ __('Use selected') }}
                </x-admin.button>
            </div>
        </div>
    </x-slot:footer>
</x-admin.modal>
