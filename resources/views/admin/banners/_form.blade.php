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
                onfocus="setTimeout(() => { this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth; }, 0)"
                onclick="this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth;"
                onmouseup="return false;"
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
                onfocus="setTimeout(() => { this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth; }, 0)"
                onclick="this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth;"
                onmouseup="return false;"
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
                onfocus="setTimeout(() => { this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth; }, 0)"
                onclick="this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth;"
                onmouseup="return false;"
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
                onfocus="setTimeout(() => { this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth; }, 0)"
                onclick="this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth;"
                onmouseup="return false;"
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
                onfocus="setTimeout(() => { this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth; }, 0)"
                onclick="this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth;"
                onmouseup="return false;"
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
                onfocus="setTimeout(() => { this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth; }, 0)"
                onclick="this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth;"
                onmouseup="return false;"
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
            placeholder="{{ __('placeholder.cta_link') }}"
            onfocus="setTimeout(() => { this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth; }, 0)"
            onclick="this.setSelectionRange(this.value.length, this.value.length); this.scrollLeft = this.scrollWidth;"
            onmouseup="return false;"
            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
        />
        @error('cta_link')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </label>

    <div class="grid gap-2">
        <span class="text-xs font-semibold text-slate-700">{{ __('Image') }}</span>
        <x-admin.media-picker
            name="media_id"
            :multiple="false"
            :value="old('media_id', $banner?->media_id)"
            :help="__('Choose an image from Media Library')"
        />
        @error('media_id')
            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
        @enderror
    </div>
</div>
