@props(['row1' => [], 'row2' => [], 'featured' => null, 'compact' => false])

@php
    $twTitle = $compact
        ? 'text-[0.7rem] font-semibold uppercase tracking-wider text-teal-600'
        : 'text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-teal-600/95';
    $twItem = $compact
        ? 'text-sm'
        : 'text-base';
@endphp

<div class="flex flex-col gap-8 {{ $compact ? '' : 'lg:flex-row lg:items-stretch' }}">
    <div class="min-w-0 flex-1 space-y-6 {{ $compact ? '' : 'sm:space-y-9' }}">
        <div
            class="grid {{ $compact ? 'grid-cols-1 gap-5' : 'gap-8 min-[500px]:grid-cols-2 min-[500px]:gap-x-7 lg:grid-cols-4 lg:gap-x-10' }}"
        >
            @foreach($row1 as $section)
                <div class="min-w-0" role="group" aria-label="{{ e($section['title']) }}">
                    <h3 class="{{ $twTitle }} {{ $compact ? 'mb-1.5 border-b border-slate-200/90 pb-1' : 'mb-2.5 border-b border-slate-200/90 pb-2.5' }}">
                        {{ $section['title'] }}
                    </h3>
                    <ul class="m-0 flex list-none flex-col gap-1.5 p-0" role="list">
                        @foreach($section['items'] as $item)
                            <li class="min-w-0 max-w-full">
                                <a
                                    href="{{ $item['href'] }}"
                                    class="group/nav relative -mx-0.5 block w-full min-w-0 max-w-full rounded-md px-0.5 py-0.5 text-left font-medium leading-tight text-slate-800 {{ $twItem }} transition after:absolute after:bottom-0.5 after:left-0.5 after:h-px after:w-0 after:bg-emerald-600/90 after:transition-all after:duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-emerald-500/40 hover:translate-x-0.5 hover:text-emerald-700/95 hover:after:w-[calc(100%-0.25rem)]"
                                    title="{{ e($item['label']) }}"
                                >{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div
            class="grid {{ $compact ? 'grid-cols-1 gap-5' : 'gap-8 min-[500px]:grid-cols-2 min-[500px]:gap-x-7 lg:grid-cols-3 lg:gap-x-10' }}"
        >
            @foreach($row2 as $section)
                <div class="min-w-0" role="group" aria-label="{{ e($section['title']) }}">
                    <h3 class="{{ $twTitle }} {{ $compact ? 'mb-1.5 border-b border-slate-200/90 pb-1' : 'mb-2.5 border-b border-slate-200/90 pb-2.5' }}">
                        {{ $section['title'] }}
                    </h3>
                    <ul class="m-0 flex list-none flex-col gap-1.5 p-0" role="list">
                        @foreach($section['items'] as $item)
                            <li class="min-w-0 max-w-full">
                                <a
                                    href="{{ $item['href'] }}"
                                    class="group/nav relative -mx-0.5 block w-full min-w-0 max-w-full rounded-md px-0.5 py-0.5 text-left font-medium leading-tight text-slate-800 {{ $twItem }} transition after:absolute after:bottom-0.5 after:left-0.5 after:h-px after:w-0 after:bg-emerald-600/90 after:transition-all after:duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-emerald-500/40 hover:translate-x-0.5 hover:text-emerald-700/95 hover:after:w-[calc(100%-0.25rem)]"
                                    title="{{ e($item['label']) }}"
                                >{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    @if($featured && ! $compact)
        <a
            href="{{ $featured['href'] }}"
            class="order-first w-full flex-shrink-0 self-stretch overflow-hidden rounded-2xl border border-slate-200/60 bg-slate-100/30 shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-500/40 max-lg:max-w-lg max-lg:mx-auto lg:order-none lg:max-w-[20rem] lg:min-w-[17rem] hover:-translate-y-0.5 hover:shadow-md"
        >
            <div class="relative h-44 w-full sm:h-52 lg:h-full lg:min-h-[14rem]">
                <img
                    src="{{ $featured['image'] }}"
                    alt="{{ $featured['image_alt'] }}"
                    class="h-full w-full object-cover"
                    loading="lazy"
                    decoding="async"
                />
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/15 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5 text-left">
                    <p class="text-sm font-semibold text-white/95">
                        {{ $featured['title'] }}
                    </p>
                    <p class="mt-0.5 text-sm text-white/80">
                        {{ $featured['subtitle'] }}
                    </p>
                </div>
            </div>
        </a>
    @endif
</div>
