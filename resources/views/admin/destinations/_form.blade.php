@php
    /** @var \App\Models\Destination|null $destination */
@endphp

@php($regions = config('destination_regions.order', []))

<div>
    <label for="destination-region" class="block text-sm font-medium text-slate-700">{{ __('region') }}</label>
    <select
        id="destination-region"
        name="region"
        @error('region') aria-invalid="true" aria-describedby="destination-region-error" @enderror
        class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        required
    >
        <option value="" disabled @selected(! old('region', $destination?->region))>{{ __('ui.select_region') }}</option>
        @foreach($regions as $key)
            <option value="{{ $key }}" @selected(old('region', $destination?->region) === $key)>{{ __('dest.region.'.$key) }}</option>
        @endforeach
    </select>
    <x-admin.error field="region" id="destination-region-error" />
</div>

<div>
    <label for="destination-name_en" class="block text-sm font-medium text-slate-700">{{ __('ui.name_en') }}</label>
    <input
        id="destination-name_en"
        name="name_en"
        @error('name_en') aria-invalid="true" aria-describedby="destination-name_en-error" @enderror
        value="{{ old('name_en', $destination?->name_en) }}"
        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        required
        autocomplete="off"
    >
    <x-admin.error field="name_en" id="destination-name_en-error" />
</div>

<div>
    <label for="destination-name_vi" class="block text-sm font-medium text-slate-700">{{ __('ui.name_vi') }}</label>
    <input
        id="destination-name_vi"
        name="name_vi"
        @error('name_vi') aria-invalid="true" aria-describedby="destination-name_vi-error" @enderror
        value="{{ old('name_vi', $destination?->name_vi) }}"
        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        required
        autocomplete="off"
    >
    <x-admin.error field="name_vi" id="destination-name_vi-error" />
</div>

<div>
    <label for="destination-slug" class="block text-sm font-medium text-slate-700">{{ __('ui.slug_optional') }}</label>
    <x-admin.hint>{{ __('admin.destinations.slug_help') }}</x-admin.hint>
    <input
        id="destination-slug"
        name="slug"
        value="{{ old('slug', $destination?->slug) }}"
        @error('slug') aria-invalid="true" aria-describedby="destination-slug-error" @enderror
        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
    >
    <x-admin.error field="slug" id="destination-slug-error" />
</div>

<div>
    <label for="destination-description" class="block text-sm font-medium text-slate-700">{{ __('description') }}</label>
    <textarea
        id="destination-description"
        name="description"
        @error('description') aria-invalid="true" aria-describedby="destination-description-error" @enderror rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">{{ old('description', $destination?->description) }}</textarea>
    <x-admin.error field="description" id="destination-description-error" />
</div>
