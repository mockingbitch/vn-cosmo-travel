@php
    /** @var \App\Models\Tour|null $tour */
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Destination') }}</label>
    <select name="destination_id" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
        @foreach($destinations as $d)
            <option
                value="{{ $d->id }}"
                title="{{ $d->name_vi }}"
                @selected(old('destination_id', $tour?->destination_id) == $d->id)
            >{{ $d->name_en }}</option>
        @endforeach
    </select>
    @error('destination_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Title') }}</label>
    <input name="title" value="{{ old('title', $tour?->title) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
    @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Slug (optional)') }}</label>
    <input name="slug" value="{{ old('slug', $tour?->slug) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" placeholder="{{ __('auto from title') }}">
    @error('slug')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Description') }}</label>
    <textarea name="description" rows="5" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">{{ old('description', $tour?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('Duration (days)') }}</label>
        <input type="number" name="duration" value="{{ old('duration', $tour?->duration) }}" min="1" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
        @error('duration')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">{{ __('Price (VND)') }}</label>
        <input type="number" name="price" value="{{ old('price', $tour?->price) }}" min="0" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
        @error('price')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Thumbnail URL') }}</label>
    <input name="thumbnail" value="{{ old('thumbnail', $tour?->thumbnail) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" placeholder="https://...">
    @error('thumbnail')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
