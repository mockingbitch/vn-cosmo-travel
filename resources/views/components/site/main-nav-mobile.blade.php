@props([
    'destinationGroups',
])

<nav x-data="{ t: false, c: false, d: false }" class="w-full" aria-label="{{ __('Mobile navigation') }}">
    <div class="grid gap-1 text-sm font-medium text-slate-700">
        <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('home') }}">{{ __('Home') }}</a>

        <div class="rounded-lg border border-slate-100">
            <button type="button" class="flex w-full items-center justify-between px-3 py-2 text-left font-semibold" @click="t = !t" :aria-expanded="t">
                {{ __('nav.tours_menu') }}
                <span class="text-slate-400" x-text="t ? '−' : '+'"></span>
            </button>
            <div x-show="t" x-transition class="space-y-0.5 border-t border-slate-100 px-2 pb-2" x-cloak>
                <a class="block rounded-md px-2 py-1.5 hover:bg-slate-50" href="{{ route('tours.index') }}">{{ __('nav.tours_all') }}</a>
                <a class="block rounded-md px-2 py-1.5 pl-3 text-slate-600 hover:bg-slate-50" href="{{ route('tours.index', ['duration' => '1-3']) }}">{{ __('nav.tours_1_3d') }}</a>
                <a class="block rounded-md px-2 py-1.5 pl-3 text-slate-600 hover:bg-slate-50" href="{{ route('tours.index', ['duration' => '4-7']) }}">{{ __('nav.tours_4_7d') }}</a>
                <a class="block rounded-md px-2 py-1.5 pl-3 text-slate-600 hover:bg-slate-50" href="{{ route('tours.index', ['duration' => '8+']) }}">{{ __('nav.tours_8plus') }}</a>
            </div>
        </div>

        <div class="rounded-lg border border-slate-100">
            <button type="button" class="flex w-full items-center justify-between px-3 py-2 text-left font-semibold" @click="c = !c" :aria-expanded="c">
                {{ __('nav.cruises_menu') }}
                <span class="text-slate-400" x-text="c ? '−' : '+'"></span>
            </button>
            <div x-show="c" x-transition class="space-y-0.5 border-t border-slate-100 px-2 pb-2" x-cloak>
                <a class="block rounded-md px-2 py-1.5 text-slate-600 hover:bg-slate-50" href="{{ route('tours.index', ['destination' => 'ha-long-bay']) }}">{{ __('nav.cruise_halong') }}</a>
                <a class="block rounded-md px-2 py-1.5 text-slate-600 hover:bg-slate-50" href="{{ route('tours.index', ['destination' => 'hanoi', 'duration' => '1-3']) }}">{{ __('nav.cruise_hanoi_short') }}</a>
                <a class="block rounded-md px-2 py-1.5 text-slate-600 hover:bg-slate-50" href="{{ route('tours.index', ['destination' => 'ho-chi-minh-city']) }}">{{ __('nav.cruise_mekong') }}</a>
            </div>
        </div>

        <div class="rounded-lg border border-slate-100">
            <button type="button" class="flex w-full items-center justify-between px-3 py-2 text-left font-semibold" @click="d = !d" :aria-expanded="d">
                {{ __('nav.destinations_menu') }}
                <span class="text-slate-400" x-text="d ? '−' : '+'"></span>
            </button>
            <div x-show="d" x-transition class="border-t border-slate-100 px-2 pb-3 pt-2 sm:px-3" x-cloak>
                <div
                    class="max-h-[min(75vh,36rem)] overflow-y-auto py-1 pl-0.5 pr-1 [scrollbar-gutter:stable]"
                >
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-x-4 sm:gap-y-6">
                        @foreach($destinationGroups as $group)
                            <div class="min-w-0">
                                <h3
                                    class="mb-2 w-full min-w-0 truncate border-b border-teal-200/80 pb-1.5 text-left text-xs font-bold uppercase leading-tight tracking-wide text-teal-600"
                                    title="{{ e($group['label']) }}"
                                >
                                    {{ $group['label'] }}
                                </h3>
                                <ul class="flex w-full list-none flex-col gap-0.5 p-0" role="list">
                                    @foreach($group['items'] as $destination)
                                        <li class="min-w-0 max-w-full">
                                            <a
                                                class="block w-full min-w-0 max-w-full truncate rounded py-0.5 text-left text-sm leading-tight text-slate-800 hover:text-teal-700"
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
                    class="mt-3 block border-t border-slate-200/80 px-1 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-100"
                    href="{{ route('home') }}#destinations"
                >{{ __('nav.see_all_destinations') }}</a>
            </div>
        </div>

        <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('blog.index') }}">{{ __('Blog') }}</a>
        <a class="rounded-lg px-3 py-2 hover:bg-slate-50" href="{{ route('home') }}#contact">{{ __('Contact') }}</a>
    </div>
</nav>
