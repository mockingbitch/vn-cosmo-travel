@extends('admin.layouts.app')

@section('content')
    <div class="grid gap-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Media') }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ __('admin.media.library_intro') }}</p>
            </div>

            <form method="GET" class="flex w-full max-w-xl flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                <input
                    type="search"
                    name="q"
                    value="{{ $q ?? '' }}"
                    placeholder="{{ __('placeholder.media_filename') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                />
                <x-admin.button type="submit" variant="secondary">
                    <x-icon name="search" size="sm" />
                    {{ __('Filter') }}
                </x-admin.button>
            </form>
        </div>

        <x-admin.card :title="__('Upload')" :subtitle="__('admin.media.upload_drag_hint')">
            <form
                method="POST"
                action="{{ route('admin.media.store') }}"
                enctype="multipart/form-data"
                class="grid gap-3"
                x-data="{
                    dragging: false,
                    pick() { this.$refs.input.click(); },
                    dropped(e) {
                        this.dragging = false;
                        const files = [...e.dataTransfer.files].filter((f) => f.type.startsWith('image/'));
                        if (!files.length) return;
                        const dt = new DataTransfer();
                        files.forEach((f) => dt.items.add(f));
                        this.$refs.input.files = dt.files;
                        this.$refs.form.submit();
                    },
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
                            <x-icon name="arrow-up-tray" size="lg" />
                        </div>
                        <div class="text-sm font-semibold text-slate-900">{{ __('Drop files here') }}</div>
                        <div class="text-xs text-slate-600">{{ __('Or') }}</div>
                        <x-admin.button type="button" variant="primary" @click="pick()">
                            <x-icon name="arrow-up-tray" size="sm" />
                            {{ __('Choose files') }}
                        </x-admin.button>
                        <div class="text-xs text-slate-500">{{ __('admin.media.upload_formats_hint') }}</div>
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
                        >
                            <x-icon name="check" size="sm" />
                            {{ __('Select all on this page') }}
                        </x-admin.button>
                        <x-admin.button type="button" variant="ghost" @click="clearSelection()">
                            <x-icon name="close" size="sm" />
                            {{ __('Clear selection') }}
                        </x-admin.button>
                        <x-admin.button type="button" variant="danger" @click="bulkConfirmOpen = true">
                            <x-icon name="delete" size="sm" />
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
                                <x-icon name="check" size="sm" />
                            </button>

                            {{-- Real Preview button — only this triggers the preview modal. --}}
                            <button
                                type="button"
                                class="absolute bottom-2 right-2 inline-flex cursor-pointer items-center gap-2 rounded-xl bg-white/95 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm ring-1 ring-black/5 transition hover:bg-white hover:text-indigo-700 hover:ring-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-300/60"
                                @click="openPreview({{ \Illuminate\Support\Js::from($previewPayload) }})"
                            >
                                <x-icon name="eye" size="sm" />
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
                            <x-admin.button type="button" variant="secondary" @click="copy(preview.url)">
                                <x-icon name="document" size="sm" />
                                {{ __('Copy URL') }}
                            </x-admin.button>

                            <x-admin.button
                                type="button"
                                variant="ghost"
                                @click="openDeleteConfirm(preview.deleteUrl)"
                            >
                                <x-icon name="delete" size="sm" />
                                {{ __('Delete') }}
                            </x-admin.button>

                            <x-admin.button type="button" variant="primary" @click="closePreview()">
                                <x-icon name="close" size="sm" />
                                {{ __('Close') }}
                            </x-admin.button>
                        </div>
                    </div>
                </x-admin.modal>

                <x-admin.modal name="bulkConfirmOpen" size="sm" :show-close="false" :aria-label="__('Confirm bulk delete')">
                    <div class="grid gap-5">
                        <div class="flex items-start gap-4">
                            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-rose-50 ring-4 ring-rose-50/60">
                                <x-icon name="exclamation-triangle" size="md" class="text-rose-600" />
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
                                <x-icon name="close" size="sm" />
                                {{ __('Cancel') }}
                            </x-admin.button>

                            <form method="POST" action="{{ route('admin.media.bulkDestroy') }}" @submit="bulkSubmitting = true">
                                @csrf
                                @method('DELETE')
                                @if(request()->filled('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
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
                                    <x-icon name="delete" size="sm" />
                                    <span x-show="!bulkSubmitting">{{ __('Delete selected') }}</span>
                                    <span x-show="bulkSubmitting" x-cloak>{{ __('Deleting…') }}</span>
                                </x-admin.button>
                            </form>
                        </div>
                    </div>
                </x-admin.modal>

                <x-admin.modal name="confirmDeleteOpen" size="sm" :show-close="false" :aria-label="__('Confirm delete')">
                    <div class="grid gap-5" x-data="{ submitting: false }">
                        <div class="flex items-start gap-4">
                            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-rose-50 ring-4 ring-rose-50/60">
                                <x-icon name="exclamation-triangle" size="md" class="text-rose-600" />
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
                                <x-icon name="close" size="sm" />
                                {{ __('Cancel') }}
                            </x-admin.button>

                            <form method="POST" :action="confirmDeleteUrl" @submit="submitting = true">
                                @csrf
                                @method('DELETE')
                                @if(request()->filled('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif
                                @if(request()->filled('page'))
                                    <input type="hidden" name="page" value="{{ request('page') }}">
                                @endif
                                @if(request()->filled('sort'))
                                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                                @endif
                                <x-admin.button type="submit" variant="danger" x-bind:disabled="submitting">
                                    <x-icon name="delete" size="sm" />
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

