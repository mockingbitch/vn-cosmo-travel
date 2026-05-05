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
                    <span class="text-xs font-medium text-slate-500">{{ __('ui.per_person') }}</span>
                </div>
                <span class="inline-flex items-center gap-2 text-sm font-semibold text-slate-900">
                    {{ __('ui.view_detail') }}
                    <x-icon name="chevron-right" size="sm" class="transition group-hover:translate-x-0.5" />
                </span>
            </div>
        </div>
    </a>
</article>

