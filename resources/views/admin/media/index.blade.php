@extends('admin.layouts.app')

@section('content')
    <div class="grid gap-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Media') }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ __('Centralized uploads you can reuse across the site.') }}</p>
            </div>

            <form method="GET" class="flex w-full max-w-xl flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                <input
                    type="search"
                    name="q"
                    value="{{ $q ?? '' }}"
                    placeholder="{{ __('placeholder.media_filename') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                />
                <select
                    name="type"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60 sm:w-40"
                >
                    <option value="">{{ __('All') }}</option>
                    <option value="image" @selected(($type ?? null) === 'image')>{{ __('Images') }}</option>
                    <option value="video" @selected(($type ?? null) === 'video')>{{ __('Videos') }}</option>
                </select>
                <x-admin.button type="submit" variant="secondary">{{ __('Filter') }}</x-admin.button>
            </form>
        </div>

        <x-admin.card :title="__('Upload')" :subtitle="__('Drag & drop or pick files to upload')">
            <form
                method="POST"
                action="{{ route('admin.media.store') }}"
                enctype="multipart/form-data"
                class="grid gap-3"
                x-data="{
                    dragging: false,
                    pick() { this.$refs.input.click(); },
                    dropped(e) { this.dragging = false; this.$refs.input.files = e.dataTransfer.files; this.$refs.form.submit(); },
                }"
                x-ref="form"
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dropped($event)"
            >
                @csrf

                <input x-ref="input" type="file" name="files[]" multiple accept="image/*" class="hidden" @change="$refs.form.submit()" />

                <div
                    class="rounded-2xl border-2 border-dashed p-6 transition"
                    :class="dragging ? 'border-slate-900 bg-slate-50' : 'border-slate-200 bg-white'"
                >
                    <div class="flex flex-col items-center justify-center gap-2 text-center">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-900 text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15.75V18a2.25 2.25 0 0 0 2.25 2.25h13.5A2.25 2.25 0 0 0 21 18v-2.25M12 3v12m0 0-3.75-3.75M12 15l3.75-3.75"/>
                            </svg>
                        </div>
                        <div class="text-sm font-semibold text-slate-900">{{ __('Drop files here') }}</div>
                        <div class="text-xs text-slate-600">{{ __('Or') }}</div>
                        <x-admin.button type="button" variant="primary" @click="pick()">{{ __('Choose files') }}</x-admin.button>
                        <div class="text-xs text-slate-500">{{ __('Max 2MB per image. JPG/PNG/WEBP/GIF.') }}</div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="text-sm text-rose-700">
                        {{ __('Upload failed. Please check file size/type.') }}
                    </div>
                @endif
            </form>
        </x-admin.card>

        @php
            $pageMediaIds = $media->getCollection()->pluck('id')->map(fn ($v) => (int) $v)->all();
        @endphp
        <x-admin.card :title="__('Library')" :subtitle="__('Click Preview to view, or select multiple to bulk delete')">
            <div
                x-data="{
                    previewOpen: false,
                    confirmDeleteOpen: false,
                    restorePreviewOpen: false,
                    confirmDeleteUrl: '',
                    preview: { id: null, url: '', fileName: '', usedCount: 0, usages: [], deleteUrl: '', usagesUrl: '' },
                    selectedIds: [],
                    pageIds: {{ \Illuminate\Support\Js::from($pageMediaIds) }},
                    bulkConfirmOpen: false,
                    bulkSubmitting: false,
                    isSelected(id) { return this.selectedIds.includes(id); },
                    toggle(id) {
                        if (this.isSelected(id)) {
                            this.selectedIds = this.selectedIds.filter(x => x !== id);
                        } else {
                            this.selectedIds = [...this.selectedIds, id];
                        }
                    },
                    selectAllOnPage() { this.selectedIds = [...new Set([...this.selectedIds, ...this.pageIds])]; },
                    clearSelection() { this.selectedIds = []; },
                    allOnPageSelected() { return this.pageIds.length > 0 && this.pageIds.every(id => this.selectedIds.includes(id)); },
                    async openPreview(payload) {
                        this.preview = payload;
                        this.previewOpen = true;
                        this.preview.usages = [];
                        try {
                            const res = await fetch(payload.usagesUrl, { headers: { 'Accept': 'application/json' } });
                            const json = await res.json();
                            this.preview.usedCount = json.used_count ?? 0;
                            this.preview.usages = json.items ?? [];
                        } catch (e) {}
                    },
                    closePreview() { this.previewOpen = false; },
                    openDeleteConfirm(url) {
                        // Đóng preview để tránh 2 overlay modal cùng lúc.
                        this.restorePreviewOpen = this.previewOpen;
                        this.previewOpen = false;
                        this.confirmDeleteUrl = url;
                        this.confirmDeleteOpen = true;
                    },
                    closeDeleteConfirm() {
                        this.confirmDeleteOpen = false;
                        if (this.restorePreviewOpen) {
                            this.previewOpen = true;
                            this.restorePreviewOpen = false;
                        }
                    },
                    async copy(text) {
                        try { await navigator.clipboard.writeText(text); } catch (e) {}
                    },
                    keydown(e) {
                        if (!this.previewOpen) return;
                        if (e.key === 'Escape') { e.preventDefault(); this.closePreview(); }
                        if ((e.metaKey || e.ctrlKey) && (e.key === 'c' || e.key === 'C')) { e.preventDefault(); this.copy(this.preview.url); }
                    },
                }"
                @keydown.window="keydown($event)"
            >
                {{-- Bulk selection toolbar — sticky at top of card body, only when something is selected. --}}
                <div
                    x-show="selectedIds.length > 0"
                    x-cloak
                    x-transition.opacity
                    class="sticky top-2 z-10 mb-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-indigo-200 bg-indigo-50/95 px-4 py-3 shadow-sm backdrop-blur"
                >
                    <div class="flex items-center gap-3 text-sm font-semibold text-indigo-900">
                        <span class="inline-flex h-7 min-w-7 items-center justify-center rounded-full bg-indigo-600 px-2 text-xs font-bold text-white">
                            <span x-text="selectedIds.length"></span>
                        </span>
                        {{ __('items selected') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <x-admin.button
                            type="button"
                            variant="secondary"
                            x-show="!allOnPageSelected()"
                            @click="selectAllOnPage()"
                        >{{ __('Select all on this page') }}</x-admin.button>
                        <x-admin.button type="button" variant="ghost" @click="clearSelection()">{{ __('Clear selection') }}</x-admin.button>
                        <x-admin.button type="button" variant="danger" @click="bulkConfirmOpen = true">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21q.342.052.682.107m-.682-.107L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562q.34-.055.682-.107m0 0a48.111 48.111 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            {{ __('Delete selected') }}
                        </x-admin.button>
                    </div>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($media as $m)
                    @php
                        /** @var \App\Models\Media $m */
                        $url = $m->url();
                        $used = (int) ($m->usages_count ?? 0);
                        $sizeKb = (int) round(((int) $m->size) / 1024);
                        $previewPayload = [
                            'id' => (int) $m->id,
                            'url' => $url,
                            'fileName' => $m->displayName(),
                            'usedCount' => $used,
                            'usagesUrl' => route('admin.media.usages', $m),
                            'deleteUrl' => route('admin.media.destroy', $m),
                            'usages' => [],
                        ];
                    @endphp
                    @php $mediaId = (int) $m->id; @endphp
                    <article
                        class="group relative overflow-hidden rounded-2xl border bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                        :class="isSelected({{ $mediaId }}) ? 'border-indigo-500 ring-2 ring-indigo-500/40' : 'border-slate-200'"
                    >
                        <div class="relative aspect-[4/3] bg-slate-100">
                            <img src="{{ $url }}" alt="{{ $m->alt_text ?? '' }}" class="h-full w-full object-cover transition duration-200 group-hover:scale-[1.02]" loading="lazy" />

                            {{-- Decorative gradient on hover --}}
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/40 via-slate-900/0 to-slate-900/0 opacity-0 transition group-hover:opacity-100"></div>

                            {{-- Multi-select checkbox: visible on hover, always-visible when selected. --}}
                            <button
                                type="button"
                                class="absolute left-2 top-2 grid h-7 w-7 cursor-pointer place-items-center rounded-lg shadow-sm ring-1 transition focus:outline-none focus:ring-2 focus:ring-indigo-300/60"
                                :class="isSelected({{ $mediaId }}) ? 'bg-indigo-600 text-white ring-indigo-600 opacity-100' : 'bg-white/95 text-transparent ring-slate-200 opacity-0 group-hover:opacity-100'"
                                :aria-pressed="isSelected({{ $mediaId }}).toString()"
                                aria-label="{{ __('Select') }}"
                                @click="toggle({{ $mediaId }})"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </button>

                            {{-- Real Preview button — only this triggers the preview modal. --}}
                            <button
                                type="button"
                                class="absolute bottom-2 right-2 inline-flex cursor-pointer items-center gap-1.5 rounded-xl bg-white/95 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm ring-1 ring-black/5 transition hover:bg-white hover:text-indigo-700 hover:ring-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-300/60"
                                @click="openPreview({{ \Illuminate\Support\Js::from($previewPayload) }})"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.643C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .638C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                {{ __('Preview') }}
                            </button>
                        </div>

                        <div class="p-3">
                            <div class="truncate text-sm font-semibold text-slate-900">{{ $m->displayName() }}</div>
                            <div class="mt-1 flex items-center justify-between gap-2 text-xs font-medium text-slate-500">
                                <span>{{ $sizeKb }} KB</span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-900/5 px-2 py-0.5 text-slate-700">
                                    {{ __('Used') }}: {{ $used }}
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
                </div>{{-- /.grid --}}

                <div class="mt-6">
                    {{ $media->links() }}
                </div>

                <x-admin.modal name="previewOpen" :title="__('Preview')">
                    <div class="grid gap-4">
                        <div class="h-[60vh] max-w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 p-2">
                            <div class="grid h-full w-full place-items-center">
                                <img
                                    :src="preview.url"
                                    class="block h-full w-full max-w-full object-contain"
                                    alt=""
                                />
                            </div>
                        </div>

                        <div class="grid gap-2">
                            <div class="text-sm font-semibold text-slate-900" x-text="preview.fileName"></div>
                            <div class="text-xs font-semibold text-slate-500">
                                {{ __('Used') }}: <span x-text="preview.usedCount"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <x-admin.button type="button" variant="secondary" @click="copy(preview.url)">{{ __('Copy URL') }}</x-admin.button>

                            <x-admin.button
                                type="button"
                                variant="ghost"
                                @click="openDeleteConfirm(preview.deleteUrl)"
                            >{{ __('Delete') }}</x-admin.button>

                            <x-admin.button type="button" variant="primary" @click="closePreview()">{{ __('Close') }}</x-admin.button>
                        </div>
                    </div>
                </x-admin.modal>

                <x-admin.modal name="bulkConfirmOpen" size="sm" :show-close="false">
                    <div class="grid gap-5">
                        <div class="flex items-start gap-4">
                            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-rose-50 ring-4 ring-rose-50/60">
                                <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-7.036A11.96 11.96 0 0 1 12 21.75c-2.676 0-5.216-.583-7.499-1.632a.75.75 0 0 1-.387-.91A11.95 11.95 0 0 0 4.5 12.75 8.25 8.25 0 0 1 12 4.71Zm.75 4.04v3.75M12 16.5h.008v.008H12V16.5Z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-base font-semibold text-slate-900">{{ __('Confirm bulk delete') }}</div>
                                <p class="mt-1 text-sm text-slate-600">
                                    {{ __('Are you sure you want to delete the selected media?') }}
                                </p>
                                <p class="mt-3 inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-800">
                                    <span class="grid h-5 w-5 place-items-center rounded-full bg-indigo-600 text-[10px] font-bold text-white" x-text="selectedIds.length"></span>
                                    {{ __('items selected') }}
                                </p>
                                <p class="mt-2 text-xs font-medium text-slate-500">{{ __('This action cannot be undone.') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <x-admin.button type="button" variant="secondary" @click="bulkConfirmOpen = false" x-bind:disabled="bulkSubmitting">
                                {{ __('Cancel') }}
                            </x-admin.button>

                            <form method="POST" action="{{ route('admin.media.bulkDestroy') }}" @submit="bulkSubmitting = true">
                                @csrf
                                @method('DELETE')
                                @if(request()->filled('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif
                                @if(request()->filled('type'))
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                @endif
                                @if(request()->filled('page'))
                                    <input type="hidden" name="page" value="{{ request('page') }}">
                                @endif
                                @if(request()->filled('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id" />
                                </template>
                                <x-admin.button type="submit" variant="danger" x-bind:disabled="bulkSubmitting">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21q.342.052.682.107m-.682-.107L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562q.34-.055.682-.107m0 0a48.111 48.111 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    <span x-show="!bulkSubmitting">{{ __('Delete selected') }}</span>
                                    <span x-show="bulkSubmitting" x-cloak>{{ __('Deleting…') }}</span>
                                </x-admin.button>
                            </form>
                        </div>
                    </div>
                </x-admin.modal>

                <x-admin.modal name="confirmDeleteOpen" size="sm" :show-close="false">
                    <div class="grid gap-5" x-data="{ submitting: false }">
                        <div class="flex items-start gap-4">
                            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-rose-50 ring-4 ring-rose-50/60">
                                <svg class="h-5 w-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-7.036A11.96 11.96 0 0 1 12 21.75c-2.676 0-5.216-.583-7.499-1.632a.75.75 0 0 1-.387-.91A11.95 11.95 0 0 0 4.5 12.75 8.25 8.25 0 0 1 12 4.71Zm.75 4.04v3.75M12 16.5h.008v.008H12V16.5Z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-base font-semibold text-slate-900">{{ __('Confirm delete') }}</div>
                                <p class="mt-1 text-sm text-slate-600">{{ __('Delete this media?') }}</p>
                                <p class="mt-3 truncate rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-800" x-text="preview.fileName"></p>
                                <p class="mt-2 text-xs font-medium text-slate-500">{{ __('This action cannot be undone.') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <x-admin.button type="button" variant="secondary" @click="closeDeleteConfirm()" x-bind:disabled="submitting">
                                {{ __('Cancel') }}
                            </x-admin.button>

                            <form method="POST" :action="confirmDeleteUrl" @submit="submitting = true">
                                @csrf
                                @method('DELETE')
                                @if(request()->filled('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif
                                @if(request()->filled('type'))
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                @endif
                                @if(request()->filled('page'))
                                    <input type="hidden" name="page" value="{{ request('page') }}">
                                @endif
                                @if(request()->filled('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                                <x-admin.button type="submit" variant="danger" x-bind:disabled="submitting">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21q.342.052.682.107m-.682-.107L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562q.34-.055.682-.107m0 0a48.111 48.111 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    <span x-show="!submitting">{{ __('Delete') }}</span>
                                    <span x-show="submitting" x-cloak>{{ __('Deleting…') }}</span>
                                </x-admin.button>
                            </form>
                        </div>
                    </div>
                </x-admin.modal>
            </div>
        </x-admin.card>
    </div>
@endsection

