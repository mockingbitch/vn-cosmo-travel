@php
    /** @var \App\Models\Destination|null $destination */
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Name') }}</label>
    <input name="name" value="{{ old('name', $destination?->name) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60" required>
    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Slug (optional)') }}</label>
    <input name="slug" value="{{ old('slug', $destination?->slug) }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
    @error('slug')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">{{ __('Description') }}</label>
    <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">{{ old('description', $destination?->description) }}</textarea>
    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>
