@props([
    'name' => 'media_id',
    'multiple' => false,
    // int|array<int>|null
    'value' => null,
    'label' => null,
    'help' => null,
    // When true (single mode only), always POST the field — empty string if nothing selected (e.g. clear previous media).
    'submitWhenEmpty' => false,
    // When false, hide the “Open media library” link (full index page).
    'showOpenLibraryLink' => true,
    // Fires `new CustomEvent(name, { detail: { url, index? } })` after single-select confirm / clear.
    'syncUrlEvent' => null,
    // Compact toolbar: no “No media selected” placeholder; for placing the button beside an input.
    'inlineToolbar' => false,
    // No hidden inputs — URL sync only (e.g. tour gallery rows). Implies name is not posted.
    'pickOnly' => false,
    // Optional row index included in sync event detail (for multi-row URL fields).
    'syncUrlIndex' => null,
    // When false, hide selected-media thumbnails (parent already shows preview, e.g. tour gallery row).
    'showSelectedPreviews' => true,
])

@php
    $isMultiple = (bool) $multiple;
    $submitWhenEmpty = (bool) $submitWhenEmpty;
    $showOpenLibraryLink = (bool) $showOpenLibraryLink;
    $inlineToolbar = (bool) $inlineToolbar;
    $pickOnly = (bool) $pickOnly;
    $syncUrlEventJs = ($syncUrlEvent !== null && $syncUrlEvent !== '') ? \Illuminate\Support\Js::from((string) $syncUrlEvent) : 'null';
    $syncUrlIndexJs = $syncUrlIndex !== null ? (int) $syncUrlIndex : 'null';
    $showSelectedPreviews = (bool) ($showSelectedPreviews ?? true);
    $initialIds = [];
    if ($isMultiple) {
        $initialIds = is_array($value) ? array_values(array_filter($value, fn ($v) => is_numeric($v))) : [];
    } else {
        $initialIds = is_numeric($value) ? [(int) $value] : [];
    }
    $postMediaId = ! $pickOnly && $name !== null && $name !== '';
@endphp

<div
    @class([
        'grid gap-2' => ! $inlineToolbar,
        'flex flex-col gap-2' => $inlineToolbar,
    ])
    x-data="mediaPicker({
        name: {{ $postMediaId ? \Illuminate\Support\Js::from($name) : 'null' }},
        pickOnly: {{ $pickOnly ? 'true' : 'false' }},
        multiple: {{ $isMultiple ? 'true' : 'false' }},
        initialIds: {{ \Illuminate\Support\Js::from($initialIds) }},
        pickerUrl: {{ \Illuminate\Support\Js::from(route('admin.media.picker')) }},
        byIdsUrl: {{ \Illuminate\Support\Js::from(route('admin.media.byIds')) }},
        syncUrlEvent: {{ $syncUrlEventJs }},
        syncUrlIndex: {{ $syncUrlIndexJs }},
    })"
    @keydown.escape.window="if (openModal) { openModal = false }"
>
    @if($label)
        <div class="text-xs font-semibold text-slate-700">{{ $label }}</div>
    @endif
    @if($help)
        <div class="text-xs text-slate-500">{{ $help }}</div>
    @endif

    @if($postMediaId)
        @if($isMultiple)
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" :name="`${name}[]`" :value="id" />
            </template>
        @elseif($submitWhenEmpty)
            <input type="hidden" :name="name" :value="selectedIds[0] ?? ''" />
        @else
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" :name="name" :value="id" />
            </template>
        @endif
    @endif

    <x-admin.media-picker-inner
        :inline-toolbar="$inlineToolbar"
        :show-open-library-link="$showOpenLibraryLink"
        :show-selected-previews="$showSelectedPreviews"
    />
</div>

<script>
    function mediaPicker({ name, pickOnly, multiple, initialIds, pickerUrl, byIdsUrl, syncUrlEvent, syncUrlIndex }) {
        return {
            name: name ?? null,
            pickOnly: pickOnly ?? false,
            multiple,
            syncUrlEvent: syncUrlEvent ?? null,
            syncUrlIndex: syncUrlIndex ?? null,
            openModal: false,
            q: '',
            items: [],
            nextPageUrl: null,
            selectedIds: initialIds ?? [],
            selected: [],

            syncDetail(url) {
                const detail = { url: url ?? '' };
                if (typeof this.syncUrlIndex === 'number') {
                    detail.index = this.syncUrlIndex;
                }
                return detail;
            },

            open() {
                this.openModal = true;
                this.$nextTick(() => {
                    try { this.$refs.search?.focus(); } catch (e) {}
                });
            },

            modalKeydown(e) {
                if (!this.openModal) return;
                if (e.key === 'Escape') { e.preventDefault(); this.openModal = false; }
                if (e.key === 'Enter' && !this.multiple && this.selectedIds.length === 1) {
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
                if (this.syncUrlEvent && ! this.multiple && this.selectedIds.length === 0) {
                    window.dispatchEvent(new CustomEvent(this.syncUrlEvent, { detail: this.syncDetail('') }));
                }
            },

            confirm() {
                const map = new Map(this.items.map(i => [i.id, i]));
                const prevById = new Map(this.selected.map(m => [m.id, m]));
                this.selected = this.selectedIds
                    .map(id => map.get(id) ?? prevById.get(id))
                    .filter(Boolean);
                if (! this.multiple && this.syncUrlEvent && this.selected.length === 1 && this.selected[0].url) {
                    window.dispatchEvent(new CustomEvent(this.syncUrlEvent, { detail: this.syncDetail(this.selected[0].url) }));
                }
                this.openModal = false;
            },
        };
    }
</script>
