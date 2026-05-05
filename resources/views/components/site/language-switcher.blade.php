@props([
    'align' => 'end',
    'block' => false,
])

@php
    use Illuminate\Support\Str;

    $supported = (array) config('locales.supported', []);
    $currentLocale = app()->getLocale();
    $current = $supported[$currentLocale] ?? null;
    $currentLabel = $current['name'] ?? $current['label'] ?? strtoupper($currentLocale);
    if ($block) {
        $panelClass = 'left-0 w-full';
    } else {
        $panelClass = $align === 'end' ? 'right-0 w-max min-w-[12rem]' : 'left-0 w-max min-w-[12rem]';
    }
    $langUid = substr(Str::uuid()->toString(), 0, 8);
    $langButtonId = 'lang-switch-btn-'.$langUid;
    $langNavId = 'lang-switch-nav-'.$langUid;
@endphp

<div
    class="relative"
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
>
    <button
        id="{{ $langButtonId }}"
        type="button"
        @if ($block) class="inline-flex w-full items-center justify-between gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-left text-sm font-medium text-slate-800 shadow-sm hover:bg-slate-50"
        @else class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-900"
        @endif
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-haspopup="menu"
        aria-controls="{{ $langNavId }}"
        @if (! $block) aria-label="{{ __('nav.header.change_language') }}: {{ $currentLabel }}" @endif
    >
        @if ($block)
            <span class="inline-flex min-w-0 flex-1 items-center gap-2.5">
                <x-icon name="globe-alt" size="md" class="shrink-0 text-slate-600" />
                <span class="min-w-0 truncate text-slate-800">{{ $currentLabel }}</span>
            </span>
            <span class="inline-flex shrink-0 text-slate-400 transition" :class="open && 'rotate-180'">
                <x-icon name="chevron-down" size="sm" />
            </span>
        @else
            <x-icon name="globe-alt" size="md" />
        @endif
    </button>

    <nav
        id="{{ $langNavId }}"
        x-show="open"
        x-transition
        x-cloak
        class="absolute z-[60] mt-1.5 max-w-[min(20rem,calc(100vw-2rem))] origin-top overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg {{ $panelClass }}"
        aria-label="{{ __('nav.header.languages') }}"
        role="menu"
    >
        @foreach ($supported as $key => $meta)
            <a
                href="{{ route('language.switch', ['locale' => $key]) }}"
                @click="open = false"
                role="menuitem"
                @if ($currentLocale === $key) aria-current="true" @endif
                class="flex items-center justify-between gap-2 px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 @if ($currentLocale === $key) bg-slate-50 font-medium text-slate-900 @endif"
            >
                <span class="min-w-0 flex-1">{{ $meta['name'] ?? $meta['label'] }}</span>
                @if ($currentLocale === $key)
                    <x-icon name="check" size="sm" class="shrink-0 text-slate-600" />
                @endif
            </a>
        @endforeach
    </nav>
</div>
