@php
    /** @var \App\Models\ServicePage $page */
    /** @var string $type */
    $codes = array_keys((array) config('locales.supported', []));
    usort($codes, fn ($a, $b) => match (true) {
        $a === 'vi' => -1,
        $b === 'vi' => 1,
        default => strcmp((string) $a, (string) $b),
    });
    /** @var array<string, mixed> $oldTranslations */
    $oldTranslations = old('translations', []);
@endphp

<p class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-600 ring-1 ring-slate-200">{{ __('admin.about.translations_help') }}</p>

@foreach ($codes as $loc)
    @php
        $meta = config('locales.supported.'.$loc);
        $label = is_array($meta) ? ($meta['name'] ?? strtoupper((string) $loc)) : strtoupper((string) $loc);
        /** @var array<string, mixed> $block */
        $block = is_array($oldTranslations[$loc] ?? null)
            ? $oldTranslations[$loc]
            : [];
        $blockTitle = isset($block['title']) ? (string) $block['title'] : $page->blockForLocale((string) $loc)['title'];
        $blockContent = isset($block['content']) ? (string) $block['content'] : $page->blockForLocale((string) $loc)['content'];
    @endphp
    <div class="rounded-xl border border-slate-100 bg-slate-50/80 p-4">
        <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $label }}</div>

        <div class="mt-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('Title') }}</label>
                <input
                    type="text"
                    name="translations[{{ $loc }}][title]"
                    value="{{ $blockTitle }}"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    required
                >
                @error('translations.'.$loc.'.title')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
                @if ($loop->first)
                    <p class="mt-1 text-xs text-slate-500">{{ __('admin.service_pages.path_fixed_help', ['path' => '/'.$type]) }}</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('Content (HTML allowed)') }}</label>
                <p class="mt-1 text-xs text-slate-500">
                    {{ __('Use wordtohtml.net to format blog content.') }}
                    <a class="font-semibold text-slate-700 underline underline-offset-4 hover:text-slate-900" href="https://wordtohtml.net/" target="_blank" rel="noopener noreferrer">wordtohtml.net</a>.
                    {{ __('We will remove all font-family styles when saving.') }}
                </p>
                <textarea
                    name="translations[{{ $loc }}][content]"
                    rows="14"
                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 font-mono text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    required
                >{{ $blockContent }}</textarea>
                @error('translations.'.$loc.'.content')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
@endforeach
