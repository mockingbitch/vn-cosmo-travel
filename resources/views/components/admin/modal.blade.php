@props([
    'name',
    'title' => null,
    'subtitle' => null,
    'size' => 'md', // sm | md | lg | xl
    'showClose' => true,
    'padding' => true,
])

@php
    $sizeClass = match ($size) {
        'sm' => 'max-w-md',
        'lg' => 'max-w-3xl',
        'xl' => 'max-w-5xl',
        default => 'max-w-lg',
    };
    $hasHeader = isset($header);
    $hasFooter = isset($footer);
    $bodyPadding = $padding ? 'px-6 py-5' : '';
@endphp

<div
    x-show="{{ $name }}"
    x-effect="if ({{ $name }}) { $store.scrollLock.lock(); } else { $store.scrollLock.unlock(); }"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center p-4 py-8"
    aria-modal="true"
    role="dialog"
>
    <div class="absolute inset-0 bg-slate-900/55 backdrop-blur-[3px]" @click="{{ $name }} = false"></div>

    <div
        x-show="{{ $name }}"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-120"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
        class="relative my-auto flex w-full {{ $sizeClass }} max-h-[min(90dvh,calc(100vh-4rem))] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
    >
        @if($title || $subtitle || $showClose || $hasHeader)
            <div class="flex shrink-0 items-start justify-between gap-4 border-b border-slate-200/80 bg-white px-6 py-4">
                <div class="min-w-0">
                    @if($title)
                        <div class="truncate text-base font-semibold text-slate-900">{{ $title }}</div>
                    @endif
                    @if($subtitle)
                        <div class="mt-0.5 truncate text-xs text-slate-500">{{ $subtitle }}</div>
                    @endif
                    @if($hasHeader)
                        <div @class(['mt-3' => $title || $subtitle])>{{ $header }}</div>
                    @endif
                </div>
                @if($showClose)
                    <button
                        type="button"
                        class="shrink-0 rounded-lg p-1.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                        @click="{{ $name }} = false"
                        aria-label="Close"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                        </svg>
                    </button>
                @endif
            </div>
        @endif

        <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain {{ $bodyPadding }}">
            {{ $slot }}
        </div>

        @if($hasFooter)
            <div class="shrink-0 border-t border-slate-200/80 bg-slate-50/80 px-6 py-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
