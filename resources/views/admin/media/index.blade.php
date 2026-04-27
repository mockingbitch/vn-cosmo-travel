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
                    placeholder="{{ __('Search by filename') }}"
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

        <x-admin.card :title="__('Library')" :subtitle="__('Click to preview, copy URL, or delete')">
            <div
                class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4"
                x-data="{
                    previewOpen: false,
                    preview: { id: null, url: '', fileName: '', usedCount: 0, usages: [], deleteUrl: '', usagesUrl: '' },
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
                @foreach($media as $m)
                    @php
                        /** @var \App\Models\Media $m */
                        $url = $m->url();
                        $used = (int) ($m->usages_count ?? 0);
                        $sizeKb = (int) round(((int) $m->size) / 1024);
                    @endphp
                    <button
                        type="button"
                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-200/60 hover:-translate-y-0.5 hover:shadow-md"
                        @click="openPreview({
                            id: {{ (int) $m->id }},
                            url: {{ \Illuminate\Support\Js::from($url) }},
                            fileName: {{ \Illuminate\Support\Js::from($m->displayName()) }},
                            usedCount: {{ (int) $used }},
                            usagesUrl: {{ \Illuminate\Support\Js::from(route('admin.media.usages', $m)) }},
                            deleteUrl: {{ \Illuminate\Support\Js::from(url('/admin/media/'.$m->id)) }},
                            usages: [],
                        })"
                    >
                        <div class="aspect-[4/3] bg-slate-100">
                            <img src="{{ $url }}" alt="{{ $m->alt_text ?? '' }}" class="h-full w-full object-cover transition duration-200 group-hover:scale-[1.02]" loading="lazy" />
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

                        <!-- Hover actions overlay (Drive-style) -->
                        <div class="pointer-events-none absolute inset-0 opacity-0 transition group-hover:opacity-100 group-focus:opacity-100">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/45 via-slate-900/0 to-slate-900/10"></div>
                            <div class="absolute left-3 top-3 inline-flex items-center gap-2">
                                <span class="rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-slate-800 shadow-sm">
                                    {{ __('Used') }}: {{ $used }}
                                </span>
                            </div>
                            <div class="absolute right-3 top-3 inline-flex items-center gap-2">
                                <span class="rounded-full bg-white/90 p-2 text-slate-700 shadow-sm">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m7-7H5"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="absolute bottom-3 right-3 inline-flex items-center gap-2">
                                <span class="rounded-xl bg-white/90 px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm">{{ __('Preview') }}</span>
                            </div>
                        </div>
                    </button>
                @endforeach

                <div class="sm:col-span-2 lg:col-span-4">
                    <div class="mt-6">
                        {{ $media->links() }}
                    </div>
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

                            <form method="POST" :action="preview.deleteUrl" onsubmit="return confirm('{{ __('Delete this media?') }}')">
                                @csrf
                                @method('DELETE')
                                <x-admin.button type="submit" variant="ghost">{{ __('Delete') }}</x-admin.button>
                            </form>

                            <x-admin.button type="button" variant="primary" @click="closePreview()">{{ __('Close') }}</x-admin.button>
                        </div>
                    </div>
                </x-admin.modal>
            </div>
        </x-admin.card>
    </div>
@endsection

