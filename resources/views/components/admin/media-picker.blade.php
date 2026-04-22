@props([
    'name' => 'media_id',
    'multiple' => false,
    // int|array<int>|null
    'value' => null,
    'label' => null,
    'help' => null,
])

@php
    $isMultiple = (bool) $multiple;
    $initialIds = [];
    if ($isMultiple) {
        $initialIds = is_array($value) ? array_values(array_filter($value, fn ($v) => is_numeric($v))) : [];
    } else {
        $initialIds = is_numeric($value) ? [(int) $value] : [];
    }
@endphp

<div
    class="grid gap-2"
    x-data="mediaPicker({
        name: {{ \Illuminate\Support\Js::from($name) }},
        multiple: {{ $isMultiple ? 'true' : 'false' }},
        initialIds: {{ \Illuminate\Support\Js::from($initialIds) }},
        pickerUrl: {{ \Illuminate\Support\Js::from(route('admin.media.picker')) }},
        byIdsUrl: {{ \Illuminate\Support\Js::from(route('admin.media.byIds')) }},
    })"
    @keydown.escape.window="if (openModal) { openModal = false }"
>
    @if($label)
        <div class="text-xs font-semibold text-slate-700">{{ $label }}</div>
    @endif
    @if($help)
        <div class="text-xs text-slate-500">{{ $help }}</div>
    @endif

    <template x-for="id in selectedIds" :key="id">
        <input type="hidden" :name="multiple ? `${name}[]` : name" :value="id" />
    </template>

    <div class="flex flex-wrap items-center gap-3">
        <template x-if="selected.length === 0">
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600">
                {{ __('No media selected') }}
            </div>
        </template>

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

        <x-admin.button type="button" variant="secondary" @click="open()">{{ __('Choose from library') }}</x-admin.button>
        <a class="text-xs font-semibold text-slate-600 hover:text-slate-900 underline underline-offset-4" href="{{ route('admin.media.index') }}" target="_blank" rel="noopener noreferrer">{{ __('Open media library') }}</a>
    </div>

    <x-admin.modal name="openModal" :title="__('Media library')">
        <div class="grid gap-4" x-init="load()" @keydown.window="modalKeydown($event)">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <input
                    type="search"
                    placeholder="{{ __('Search by filename') }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    x-model.debounce.300ms="q"
                    @input="reload()"
                    x-ref="search"
                />
                <div class="flex items-center gap-2">
                    <button type="button" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" @click="type = ''; reload()">{{ __('All') }}</button>
                    <button type="button" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50" @click="type = 'image'; reload()">{{ __('Images') }}</button>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <template x-for="m in items" :key="m.id">
                    <button
                        type="button"
                        class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-200/60 hover:-translate-y-0.5 hover:shadow-md"
                        @click="toggle(m)"
                        @keydown.enter.prevent="toggle(m)"
                        :aria-pressed="selectedIds.includes(m.id).toString()"
                    >
                        <div class="aspect-[4/3] bg-slate-100">
                            <img :src="m.url" class="h-full w-full object-cover transition duration-200 group-hover:scale-[1.02]" alt="" />
                        </div>
                        <div class="p-3">
                            <div class="truncate text-sm font-semibold text-slate-900" x-text="m.file_name"></div>
                            <div class="mt-1 flex items-center justify-between gap-2 text-xs font-medium text-slate-500">
                                <span x-text="`${Math.round(m.size/1024)} KB`"></span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-900/5 px-2 py-0.5 text-slate-700">
                                    {{ __('Used') }}: <span x-text="m.used_count"></span>
                                </span>
                            </div>
                        </div>

                        <div class="absolute inset-0 opacity-0 transition" :class="selectedIds.includes(m.id) ? 'opacity-100' : 'opacity-0'">
                            <div class="absolute inset-0 bg-indigo-600/10"></div>
                            <div class="absolute left-3 top-3 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm">
                                {{ __('Selected') }}
                            </div>
                        </div>
                    </button>
                </template>
            </div>

            <div class="flex items-center justify-between gap-2">
                <div class="text-xs font-semibold text-slate-500">
                    <span x-text="selectedIds.length"></span> {{ __('selected') }}
                </div>
                <div class="flex items-center gap-2">
                    <x-admin.button type="button" variant="ghost" @click="openModal = false">{{ __('Close') }}</x-admin.button>
                    <x-admin.button type="button" variant="primary" @click="confirm()">{{ __('Use selected') }}</x-admin.button>
                </div>
            </div>
        </div>
    </x-admin.modal>
</div>

<script>
    function mediaPicker({ name, multiple, initialIds, pickerUrl, byIdsUrl }) {
        return {
            name,
            multiple,
            openModal: false,
            q: '',
            type: '',
            items: [],
            nextPageUrl: null,
            selectedIds: initialIds ?? [],
            selected: [],

            open() {
                this.openModal = true;
                this.$nextTick(() => {
                    try { this.$refs.search?.focus(); } catch (e) {}
                });
            },

            modalKeydown(e) {
                if (!this.openModal) return;
                if (e.key === 'Escape') { e.preventDefault(); this.openModal = false; }
                if (e.key === 'Enter' && !multiple && this.selectedIds.length === 1) {
                    // Quick confirm for single-select.
                    e.preventDefault();
                    this.confirm();
                }
            },

            async loadSelected() {
                if (!this.selectedIds.length) { this.selected = []; return; }
                const url = new URL(byIdsUrl, window.location.origin);
                url.searchParams.set('ids', this.selectedIds.join(','));
                const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                const json = await res.json();
                this.selected = json.data ?? [];
            },

            async load() {
                await this.reload();
                await this.loadSelected();
            },

            async reload() {
                const url = new URL(pickerUrl, window.location.origin);
                if (this.q) url.searchParams.set('q', this.q);
                if (this.type) url.searchParams.set('type', this.type);
                const res = await fetch(url.toString(), { headers: { 'Accept': 'application/json' } });
                const json = await res.json();
                this.items = json.data ?? [];
                this.nextPageUrl = json.next_page_url ?? null;
            },

            toggle(m) {
                const id = m.id;
                if (this.multiple) {
                    if (this.selectedIds.includes(id)) this.selectedIds = this.selectedIds.filter(x => x !== id);
                    else this.selectedIds = [...this.selectedIds, id];
                } else {
                    this.selectedIds = this.selectedIds.includes(id) ? [] : [id];
                }
            },

            remove(id) {
                this.selectedIds = this.selectedIds.filter(x => x !== id);
                this.selected = this.selected.filter(x => x.id !== id);
            },

            confirm() {
                const map = new Map(this.items.map(i => [i.id, i]));
                this.selected = this.selectedIds.map(id => map.get(id)).filter(Boolean);
                this.openModal = false;
            },
        };
    }
</script>

