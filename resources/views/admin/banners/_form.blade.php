@php
    /** @var \App\Models\HeroBanner|null $banner */
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <label class="grid gap-1 sm:col-span-2">
        <span class="text-xs font-semibold text-slate-700">{{ __('Title') }}</span>
        <input
            type="text"
            name="title"
            required
            value="{{ old('title', $banner?->title ?? '') }}"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        />
        @error('title')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </label>

    <label class="grid gap-1 sm:col-span-2">
        <span class="text-xs font-semibold text-slate-700">{{ __('Subtitle') }}</span>
        <input
            type="text"
            name="subtitle"
            value="{{ old('subtitle', $banner?->subtitle ?? '') }}"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        />
        @error('subtitle')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </label>

    <label class="grid gap-1">
        <span class="text-xs font-semibold text-slate-700">{{ __('CTA text') }}</span>
        <input
            type="text"
            name="cta_text"
            value="{{ old('cta_text', $banner?->cta_text ?? '') }}"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        />
        @error('cta_text')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </label>

    <label class="grid gap-1">
        <span class="text-xs font-semibold text-slate-700">{{ __('CTA link') }}</span>
        <input
            type="text"
            name="cta_link"
            value="{{ old('cta_link', $banner?->cta_link ?? '') }}"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        />
        @error('cta_link')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </label>

    <label class="grid gap-1">
        <span class="text-xs font-semibold text-slate-700">{{ __('Order') }}</span>
        <input
            type="number"
            name="sort_order"
            min="0"
            value="{{ old('sort_order', (string) ($banner?->sort_order ?? 0)) }}"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        />
        @error('sort_order')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </label>

    <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active', $banner?->is_active ?? true) ? 'checked' : '' }}
            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400/60"
        />
        <span class="font-semibold">{{ __('Active') }}</span>
    </label>

    <label class="grid gap-2 sm:col-span-2">
        <span class="text-xs font-semibold text-slate-700">{{ __('Image') }}</span>
        <input
            type="file"
            name="image"
            accept="image/*"
            class="block w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800"
        />
        @error('image')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror

        @if($banner?->image_path)
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                <img class="h-44 w-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image_path) }}" alt="" />
            </div>
        @endif
    </label>
</div>

