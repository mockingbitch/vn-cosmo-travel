@props([
    'name',
    'title' => null,
])

<div
    x-show="{{ $name }}"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    aria-modal="true"
    role="dialog"
>
    <div class="absolute inset-0 bg-slate-900/50" @click="{{ $name }} = false"></div>
    <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
        <div class="flex items-start justify-between gap-4 px-6 py-4">
            <div>
                @if($title)
                    <div class="text-base font-semibold text-slate-900">{{ $title }}</div>
                @endif
            </div>
            <button type="button" class="rounded-lg p-1 text-slate-500 hover:bg-slate-50 hover:text-slate-900" @click="{{ $name }} = false" aria-label="Close">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        </div>
        <div class="px-6 pb-6">
            {{ $slot }}
        </div>
    </div>
</div>

