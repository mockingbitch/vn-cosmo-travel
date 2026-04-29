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
    >{{ __('Choose from library') }}</x-admin.button>
    @if($showOpenLibraryLink)
        <a class="text-xs font-semibold text-slate-600 hover:text-slate-900 underline underline-offset-4" href="{{ route('admin.media.index') }}" target="_blank" rel="noopener noreferrer">{{ __('Open media library') }}</a>
    @endif
</div>

<x-admin.modal
    name="openModal"
    size="xl"
    :title="__('Media library')"
    :subtitle="__('Search and pick from your existing media library.')"
>
    <div x-init="load()" @keydown.window="modalKeydown($event)">
        {{-- Sticky search toolbar. Negative margins escape body padding so border-b spans full width. --}}
        <div class="sticky -top-20 z-10 -mx-6 -mt-5 mb-4 border-b border-slate-200 bg-white/95 px-6 pb-3 pt-2.5 backdrop-blur">
            <label class="relative block w-full">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.34-4.34M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" />
                    </svg>
                </span>
                <input
                    type="search"
                    placeholder="{{ __('placeholder.media_filename') }}"
                    autocomplete="off"
                    class="block w-full rounded-xl border border-slate-200 bg-white py-2 pl-9 pr-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    x-model="q"
                    @input.debounce.300ms="reload()"
                    @search="q = ($event.target && $event.target.value) ? $event.target.value : ''; reload()"
                    x-ref="search"
                />
            </label>
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
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                    </button>
                </template>
            </div>
        </template>

        {{-- Empty state --}}
        <template x-if="items.length === 0">
            <div class="grid place-items-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-6 py-16 text-center">
                <div class="grid h-12 w-12 place-items-center rounded-2xl bg-white text-slate-400 shadow-sm ring-1 ring-slate-200">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 16 5-5a2 2 0 0 1 2.828 0L17 17M14 14l1.586-1.586a2 2 0 0 1 2.828 0L21 15M14 8h.01M5 21h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2Z" />
                    </svg>
                </div>
                <div class="mt-3 text-sm font-semibold text-slate-900">
                    <span x-show="q">{{ __('No media match your search.') }}</span>
                    <span x-show="!q" x-cloak>{{ __('Your library is empty.') }}</span>
                </div>
                <p class="mt-1 max-w-sm text-xs text-slate-500">
                    {{ __('Upload new files from the') }}
                    <a class="font-semibold text-slate-700 underline underline-offset-2 hover:text-slate-900" href="{{ route('admin.media.index') }}" target="_blank" rel="noopener noreferrer">{{ __('Media library') }}</a>.
                </p>
            </div>
        </template>
    </div>

    {{-- Sticky footer: count chip + actions --}}
    <x-slot:footer>
        <div class="flex items-center justify-between gap-3">
            <div class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                <span class="grid h-5 w-5 place-items-center rounded-full bg-indigo-600 text-[10px] font-bold text-white" x-text="selectedIds.length"></span>
                {{ __('selected') }}
            </div>
            <div class="flex items-center gap-2">
                <x-admin.button type="button" variant="ghost" @click="openModal = false">{{ __('Close') }}</x-admin.button>
                <x-admin.button
                    type="button"
                    variant="primary"
                    x-bind:disabled="selectedIds.length === 0"
                    @click="confirm()"
                >{{ __('Use selected') }}</x-admin.button>
            </div>
        </div>
    </x-slot:footer>
</x-admin.modal>
