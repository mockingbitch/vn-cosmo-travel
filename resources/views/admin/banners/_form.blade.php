@php
    /** @var \App\Models\HeroBanner|null $banner */
@endphp

<div class="grid gap-8 lg:grid-cols-2 lg:gap-10">
    <div class="grid gap-4">
        <h2 class="border-b border-slate-200 pb-2 text-sm font-semibold text-slate-900">{{ __('Vietnamese') }}</h2>

        <label class="grid gap-1">
            <span class="text-xs font-semibold text-slate-700">{{ __('Title') }}</span>
            <input
                type="text"
                name="title_vi"
                required
                value="{{ old('title_vi', $banner?->getTitleForLocale('vi') ?? '') }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            />
            @error('title_vi')
                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
            @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-semibold text-slate-700">{{ __('Subtitle') }}</span>
            <input
                type="text"
                name="subtitle_vi"
                value="{{ old('subtitle_vi', $banner?->getSubtitleForLocale('vi') ?? '') }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            />
            @error('subtitle_vi')
                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
            @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-semibold text-slate-700">{{ __('CTA text') }}</span>
            <input
                type="text"
                name="cta_text_vi"
                value="{{ old('cta_text_vi', $banner?->getCtaTextForLocale('vi') ?? '') }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            />
            @error('cta_text_vi')
                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
            @enderror
        </label>
    </div>

    <div class="grid gap-4 lg:border-l lg:border-slate-200 lg:pl-10">
        <h2 class="border-b border-slate-200 pb-2 text-sm font-semibold text-slate-900">{{ __('English') }}</h2>

        <label class="grid gap-1">
            <span class="text-xs font-semibold text-slate-700">{{ __('Title') }}</span>
            <input
                type="text"
                name="title_en"
                required
                value="{{ old('title_en', $banner?->getTitleForLocale('en') ?? '') }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            />
            @error('title_en')
                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
            @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-semibold text-slate-700">{{ __('Subtitle') }}</span>
            <input
                type="text"
                name="subtitle_en"
                value="{{ old('subtitle_en', $banner?->getSubtitleForLocale('en') ?? '') }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            />
            @error('subtitle_en')
                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
            @enderror
        </label>

        <label class="grid gap-1">
            <span class="text-xs font-semibold text-slate-700">{{ __('CTA text') }}</span>
            <input
                type="text"
                name="cta_text_en"
                value="{{ old('cta_text_en', $banner?->getCtaTextForLocale('en') ?? '') }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
            />
            @error('cta_text_en')
                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
            @enderror
        </label>
    </div>
</div>

<div class="mt-8 grid gap-4 border-t border-slate-200 pt-6">
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Shared for both languages') }}</p>

    <label class="grid gap-1">
        <span class="text-xs font-semibold text-slate-700">{{ __('CTA link') }}</span>
        <input
            type="text"
            name="cta_link"
            value="{{ old('cta_link', $banner?->cta_link ?? '') }}"
            placeholder="/tours"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        />
        @error('cta_link')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </label>

    <label class="grid gap-2">
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
