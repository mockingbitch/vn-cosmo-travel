{{-- Use inside Alpine x-for="(row, idx) in rows" so `idx` resolves per row. Pick-only: no media IDs posted. --}}
<div
    class="flex flex-col gap-2"
    x-data="mediaPicker({
        name: null,
        pickOnly: true,
        multiple: false,
        initialIds: [],
        pickerUrl: @js(route('admin.media.picker')),
        byIdsUrl: @js(route('admin.media.byIds')),
        syncUrlEvent: 'gallery-url-sync',
        syncUrlIndex: idx,
    })"
    @keydown.escape.window="if (openModal) { openModal = false }"
>
    <x-admin.media-picker-inner
        :inline-toolbar="true"
        :show-open-library-link="false"
        :show-selected-previews="false"
    />
</div>
