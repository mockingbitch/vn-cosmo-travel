@props([
    'align' => 'end',
    'block' => false,
])

@php
    $supported = (array) config('locales.supported', []);
    $currentLocale = app()->getLocale();
    $current = $supported[$currentLocale] ?? null;
    $currentLabel = $current['name'] ?? $current['label'] ?? strtoupper($currentLocale);
    if ($block) {
        $panelClass = 'left-0 w-full';
    } else {
        $panelClass = $align === 'end' ? 'right-0 w-max min-w-[12rem]' : 'left-0 w-max min-w-[12rem]';
    }
@endphp

<div
    class="relative"
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
>
    <button
        type="button"
        @if ($block) class="inline-flex w-full items-center justify-between gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-left text-sm font-medium text-slate-800 shadow-sm hover:bg-slate-50"
        @else class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-900"
        @endif
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-haspopup="listbox"
        @if (! $block) aria-label="{{ __('Change language') }}: {{ $currentLabel }}" @endif
    >
        @if ($block)
            <span class="inline-flex min-w-0 flex-1 items-center gap-2.5">
                <svg
                    class="h-5 w-5 shrink-0 text-slate-600"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.944 11.944 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.902m15.686 0A8.959 8.959 0 0121 12c0 1.657-.28 3.25-.796 4.75M12 3a8.995 8.995 0 00-3.5 0M12 21a8.97 8.97 0 00.79-1.5"
                    />
                </svg>
                <span class="min-w-0 truncate text-slate-800">{{ $currentLabel }}</span>
            </span>
            <svg
                class="h-4 w-4 shrink-0 text-slate-400 transition"
                :class="open && 'rotate-180'"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
            </svg>
        @else
            <svg
                class="h-5 w-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.944 11.944 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.902m15.686 0A8.959 8.959 0 0121 12c0 1.657-.28 3.25-.796 4.75M12 3a8.995 8.995 0 00-3.5 0M12 21a8.97 8.97 0 00.79-1.5"
                />
            </svg>
        @endif
    </button>

    <div
        x-show="open"
        x-transition
        x-cloak
        class="absolute z-[60] mt-1.5 max-w-[min(20rem,calc(100vw-2rem))] origin-top overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg {{ $panelClass }}"
        role="listbox"
    >
        @foreach ($supported as $key => $meta)
            <a
                href="{{ route('language.switch', ['locale' => $key]) }}"
                @click="open = false"
                role="option"
                @if ($currentLocale === $key) aria-selected="true" @endif
                class="flex items-center justify-between gap-2 px-3 py-2.5 text-sm text-slate-700 hover:bg-slate-50 @if ($currentLocale === $key) bg-slate-50 font-medium text-slate-900 @endif"
            >
                <span class="min-w-0 flex-1">{{ $meta['name'] ?? $meta['label'] }}</span>
                @if ($currentLocale === $key)
                    <svg class="h-4 w-4 shrink-0 text-slate-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 0 1 0 1.414l-8 8a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L8 12.586l7.293-7.293a1 1 0 0 1 1.414 0Z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </a>
        @endforeach
    </div>
</div>
