@props([])

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.14em] text-white shadow-md ring-2 ring-emerald-400/40 animate-book-now-blink sm:text-xs']) }}>
    {{ __('ui.book_now') }}
</span>
