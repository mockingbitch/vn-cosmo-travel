@props([
    'name' => 'social_links',
    'label',
    'help' => null,
    'links' => [],
])

@php
    $initialLinks = count($links) > 0
        ? array_values($links)
        : [['label' => '', 'url' => '']];
@endphp

<div
    x-data="{
        links: @js($initialLinks),
        add() {
            this.links.push({ label: '', url: '' });
        },
        remove(index) {
            if (this.links.length <= 1) {
                this.links[0] = { label: '', url: '' };
                return;
            }
            this.links.splice(index, 1);
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

    <ul class="space-y-3">
        <template x-for="(link, index) in links" :key="index">
            <li class="rounded-xl border border-slate-200 bg-slate-50/50 p-3">
                <div class="grid gap-3 sm:grid-cols-2">
                    <label class="grid gap-1">
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.social.link_label') }}</span>
                        <input
                            type="text"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            x-model="links[index].label"
                            :name="'{{ $name }}[' + index + '][label]'"
                            maxlength="80"
                            placeholder="{{ __('admin.settings.social.link_label_placeholder') }}"
                        />
                    </label>
                    <label class="grid gap-1">
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.social.link_url') }}</span>
                        <input
                            type="url"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            x-model="links[index].url"
                            :name="'{{ $name }}[' + index + '][url]'"
                            maxlength="255"
                            placeholder="https://"
                        />
                    </label>
                </div>
                <div class="mt-2 flex justify-end">
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-600 shadow-sm hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"
                        @click="remove(index)"
                    >
                        <x-icon name="trash" size="sm" />
                        {{ __('admin.settings.social.remove_link') }}
                    </button>
                </div>
            </li>
        </template>
    </ul>

    <button
        type="button"
        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50"
        @click="add()"
    >
        <x-icon name="plus" size="sm" />
        {{ __('admin.settings.social.add_link') }}
    </button>

    @error($name)
        <p class="text-xs text-rose-600">{{ $message }}</p>
    @enderror
    @error($name.'.*.label')
        <p class="text-xs text-rose-600">{{ $message }}</p>
    @enderror
    @error($name.'.*.url')
        <p class="text-xs text-rose-600">{{ $message }}</p>
    @enderror
</div>
