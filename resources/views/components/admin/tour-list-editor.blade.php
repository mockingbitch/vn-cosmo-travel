@props([
    'name',
    'label',
    'help' => null,
    'items' => [],
])

@php
    $initialItems = count($items) > 0 ? array_values($items) : [''];
@endphp

<div
    x-data="{
        items: @js($initialItems),
        add() {
            this.items.push('');
        },
        remove(index) {
            if (this.items.length <= 1) {
                this.items[0] = '';
                return;
            }
            this.items.splice(index, 1);
        },
    }"
    class="space-y-3"
>
    <div>
        <div class="text-sm font-medium text-slate-700">{{ $label }}</div>
        @if ($help)
            <p class="mt-0.5 text-xs text-slate-500">{{ $help }}</p>
        @endif
    </div>

    <ul class="space-y-2">
        <template x-for="(item, index) in items" :key="index">
            <li class="flex items-start gap-2">
                <input
                    type="text"
                    class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    x-model="items[index]"
                    name="{{ $name }}[]"
                    maxlength="120"
                    placeholder="{{ __('admin.tour_form.list_item_placeholder') }}"
                />
                <button
                    type="button"
                    class="inline-flex shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white p-2 text-slate-500 shadow-sm hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"
                    @click="remove(index)"
                    aria-label="{{ __('admin.tour_form.remove_item') }}"
                    title="{{ __('admin.tour_form.remove_item') }}"
                >
                    <x-icon name="trash" size="sm" />
                </button>
            </li>
        </template>
    </ul>

    <button
        type="button"
        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
        @click="add()"
    >
        <x-icon name="plus" size="sm" />
        {{ __('admin.tour_form.add_item') }}
    </button>

    @error($name)
        <p class="text-xs text-rose-600">{{ $message }}</p>
    @enderror
    @error($name.'.*')
        <p class="text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
