@props([
    /** @var \App\ViewModels\PostCardViewModel $vm */
    'vm',
])

<article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <a href="{{ route('blog.show', $vm->slug()) }}" class="block">
        <div class="aspect-[16/10] overflow-hidden bg-slate-100">
            <img
                src="{{ $vm->thumbnailUrl() }}"
                alt="{{ $vm->title() }}"
                loading="lazy"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
            />
        </div>
        <div class="p-5 font-sans">
            <h3 class="text-base font-semibold tracking-tight text-slate-900 line-clamp-2">
                {{ $vm->title() }}
            </h3>
            <p class="mt-2 text-sm leading-6 text-slate-600 line-clamp-3">
                {{ $vm->excerpt() }}
            </p>
            <div class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-slate-900">
                {{ __('ui.read_more') }}
                <x-icon name="chevron-right" size="sm" class="transition group-hover:translate-x-0.5" />
            </div>
        </div>
    </a>
</article>

