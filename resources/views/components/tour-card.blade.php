@props([
    /** @var \App\ViewModels\TourCardViewModel $vm */
    'vm',
])

<article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <a href="{{ route('tours.show', $vm->slug()) }}" class="block">
        <div class="aspect-[16/10] overflow-hidden bg-slate-100">
            <img
                src="{{ $vm->thumbnailUrl() }}"
                alt="{{ $vm->title() }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
            />
        </div>
        <div class="p-5">
            <div class="flex items-center justify-between gap-3">
                <div class="text-xs font-semibold text-slate-500">{{ $vm->durationLabel() }}</div>
                <div class="text-xs font-semibold text-slate-500">{{ $vm->destinationName() }}</div>
            </div>
            <h3 class="mt-2 line-clamp-2 text-base font-semibold tracking-tight text-slate-900">
                {{ $vm->title() }}
            </h3>
            <div class="mt-3 flex items-center justify-between">
                <div class="text-sm font-semibold text-slate-900">
                    {{ $vm->priceLabel() }}
                    <span class="text-xs font-medium text-slate-500">{{ __('per person') }}</span>
                </div>
                <span class="inline-flex items-center gap-1 text-sm font-semibold text-slate-900">
                    {{ __('View detail') }}
                    <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            </div>
        </div>
    </a>
</article>

