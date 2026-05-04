@props([
    'destinationGroups',
])

<nav class="hidden shrink-0 items-center gap-1 text-sm font-medium text-slate-700 md:flex md:overflow-visible">
    <a class="rounded-lg px-2.5 py-1.5 hover:text-slate-900" href="{{ route('home') }}">{{ __('Home') }}</a>

    {{-- Tours: duration + quick links --}}
    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg px-2.5 py-1.5 hover:text-slate-900"
            :class="open ? 'text-slate-900' : ''"
            @click="open = !open"
            :aria-expanded="open.toString()"
        >
            {{ __('nav.tours_menu') }}
            <x-icon name="chevron-down" size="sm" class="text-slate-500" />
        </button>
        <div
            x-show="open"
            x-transition
            x-cloak
            class="absolute left-0 top-full z-50 mt-1 min-w-[16rem] rounded-2xl border border-slate-200 bg-white py-2 shadow-lg"
        >
            <a class="block px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50" href="{{ route('tours.index') }}">
                {{ __('nav.tours_all') }}
            </a>
            <div class="my-1.5 h-px bg-slate-100"></div>
            <div class="px-3 pb-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('nav.duration_label') }}</div>
            <a class="block px-4 py-1.5 text-sm text-slate-700 hover:bg-slate-50" href="{{ route('tours.index', ['duration' => '1-3']) }}">{{ __('nav.tours_1_3d') }}</a>
            <a class="block px-4 py-1.5 text-sm text-slate-700 hover:bg-slate-50" href="{{ route('tours.index', ['duration' => '4-7']) }}">{{ __('nav.tours_4_7d') }}</a>
            <a class="block px-4 py-1.5 text-sm text-slate-700 hover:bg-slate-50" href="{{ route('tours.index', ['duration' => '8+']) }}">{{ __('nav.tours_8plus') }}</a>
        </div>
    </div>

    {{-- Cruises & water experiences (filter by popular bay / delta) --}}
    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg px-2.5 py-1.5 hover:text-slate-900"
            :class="open ? 'text-slate-900' : ''"
            @click="open = !open"
            :aria-expanded="open.toString()"
        >
            {{ __('nav.cruises_menu') }}
            <x-icon name="chevron-down" size="sm" class="text-slate-500" />
        </button>
        <div
            x-show="open"
            x-transition
            x-cloak
            class="absolute left-0 top-full z-50 mt-1 min-w-[16rem] rounded-2xl border border-slate-200 bg-white py-2 shadow-lg"
        >
            <a
                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
                href="{{ route('tours.index', ['destination' => 'ha-long-bay']) }}"
            >{{ __('nav.cruise_halong') }}</a>
            <a
                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
                href="{{ route('tours.index', ['destination' => 'hanoi', 'duration' => '1-3']) }}"
            >{{ __('nav.cruise_hanoi_short') }}</a>
            <a
                class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
                href="{{ route('tours.index', ['destination' => 'ho-chi-minh-city']) }}"
            >{{ __('nav.cruise_mekong') }}</a>
        </div>
    </div>

    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg px-2.5 py-1.5 hover:text-slate-900"
            :class="open ? 'text-slate-900' : ''"
            @click="open = !open"
            :aria-expanded="open.toString()"
        >
            {{ __('nav.destinations_menu') }}
            <x-icon name="chevron-down" size="sm" class="text-slate-500" />
        </button>
        <div
            x-show="open"
            x-transition
            x-cloak
            class="absolute left-1/2 top-full z-50 mt-1.5 w-[100vw] max-w-[min(120rem,calc(100vw-2rem))] -translate-x-1/2 overflow-x-hidden overflow-y-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xl"
        >
            <div
                class="max-h-[min(78vh,32rem)] w-full min-w-0 overflow-y-auto overscroll-contain [scrollbar-gutter:stable] px-5 py-5 sm:max-h-[min(75vh,36rem)] sm:px-6 sm:py-6 md:max-h-[min(80vh,40rem)] md:px-8 md:py-7 lg:max-h-[min(80vh,44rem)] lg:px-10 lg:py-8"
            >
                <div
                    class="grid w-full grid-cols-1 gap-8 min-[500px]:grid-cols-2 min-[500px]:gap-x-6 min-[500px]:gap-y-7 md:grid-cols-2 md:gap-x-8 md:gap-y-8 lg:grid-cols-3 lg:gap-10 lg:gap-y-8 xl:grid-cols-4 xl:gap-x-10"
                >
                    @foreach($destinationGroups as $group)
                        <div class="flex min-w-0 flex-col pl-0">
                            <h3
                                class="mb-2.5 w-full min-w-0 max-w-full truncate border-b border-teal-200/80 pb-2.5 text-left text-xs font-bold uppercase leading-tight tracking-wide text-teal-600 sm:text-[0.8rem] md:leading-none"
                                title="{{ e($group['label']) }}"
                            >
                                {{ $group['label'] }}
                            </h3>
                            <ul class="mt-0 flex w-full list-none flex-col gap-0.5 p-0" role="list">
                                @foreach($group['items'] as $destination)
                                    <li class="min-w-0 max-w-full">
                                        <a
                                            class="block w-full min-w-0 max-w-full truncate rounded-md py-1 pr-0.5 text-left text-sm leading-tight text-slate-800 transition hover:bg-teal-50/80 hover:text-teal-700"
                                            href="{{ route('tours.index', ['destination' => $destination->slug]) }}"
                                            title="{{ e($destination->localizedName()) }}"
                                        >{{ $destination->localizedName() }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
            <a
                class="block w-full border-t border-slate-200/90 bg-slate-50/30 px-5 py-3.5 text-left text-sm font-semibold text-slate-800 transition hover:bg-slate-100/90 sm:px-6 md:px-8 md:py-4"
                href="{{ route('home') }}#destinations"
            >
                {{ __('nav.see_all_destinations') }}
            </a>
        </div>
    </div>

    <a class="rounded-lg px-2.5 py-1.5 hover:text-slate-900" href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
    <a class="rounded-lg px-2.5 py-1.5 hover:text-slate-900" href="{{ route('home') }}#contact">{{ __('Contact') }}</a>
</nav>
