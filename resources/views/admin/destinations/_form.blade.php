@php
    /** @var \App\Models\Destination|null $destination */
@endphp

@php($regions = config('destination_regions.order', []))

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('region') }}</label>
    <select
        name="region"
        class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        required
    >
        <option value="" disabled @selected(! old('region', $destination?->region))>{{ __('ui.select_region') }}</option>
        @foreach($regions as $key)
            <option value="{{ $key }}" @selected(old('region', $destination?->region) === $key)>{{ __('dest.region.'.$key) }}</option>
        @endforeach
    </select>
    @error('region')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('ui.name_en') }}</label>
    <input
        name="name_en"
        value="{{ old('name_en', $destination?->name_en) }}"
        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        required
        autocomplete="off"
    >
    @error('name_en')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('ui.name_vi') }}</label>
    <input
        name="name_vi"
        value="{{ old('name_vi', $destination?->name_vi) }}"
        class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        required
        autocomplete="off"
    >
    @error('name_vi')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('ui.slug_optional') }}</label>
    <input name="slug" value="{{ old('slug', $destination?->slug) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
    @error('slug')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('description') }}</label>
    <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">{{ old('description', $destination?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
