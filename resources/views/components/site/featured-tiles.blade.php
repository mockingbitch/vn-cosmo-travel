@props([
    /** @var list<array{eyebrow: string, title: string, chips: list<string>, description: string, cta_label: string, url: string, image_url: string, span: string, is_wide: bool, is_lead: bool}> $tiles */
    'tiles' => [],
])

@if($tiles !== [])
    {{-- Fixed row tracks keep the lead tile aligned with the two stacked ones.
         Tiles must not set a min-height at lg, or they overflow their track and
         overlap the row below; overflow-hidden clips long custom text instead. --}}
    <div class="mt-8 grid gap-4 sm:gap-5 lg:auto-rows-[17rem] lg:grid-cols-3">
        @foreach($tiles as $tile)
            <a
                href="{{ $tile['url'] }}"
                class="group relative flex min-h-[17rem] flex-col justify-end overflow-hidden rounded-3xl bg-slate-900 shadow-sm ring-1 ring-slate-900/5 transition hover:shadow-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900 focus-visible:ring-offset-2 lg:min-h-0 {{ $tile['span'] }}"
            >
                @if($tile['image_url'] !== '')
                    <img
                        src="{{ $tile['image_url'] }}"
                        alt="{{ $tile['title'] }}"
                        loading="lazy"
                        decoding="async"
                        class="absolute inset-0 h-full w-full object-cover transition duration-500 motion-safe:group-hover:scale-[1.04]"
                    />
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/35 to-slate-950/10"></div>

                @if($tile['eyebrow'] !== '')
                    <span class="absolute left-4 top-4 rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-white backdrop-blur sm:left-5 sm:top-5">
                        {{ $tile['eyebrow'] }}
                    </span>
                @endif

                <span class="absolute right-4 top-4 grid h-8 w-8 place-items-center rounded-full bg-white/15 text-amber-300 backdrop-blur transition group-hover:bg-white/30 sm:right-5 sm:top-5" aria-hidden="true">
                    <x-icon name="arrow-up-right" size="sm" />
                </span>

                <div class="relative p-4 sm:p-5 lg:p-6">
                    <h3 @class([
                        'font-serif text-white',
                        'text-2xl sm:text-4xl lg:text-5xl' => $tile['is_lead'],
                        'text-2xl sm:text-3xl' => ! $tile['is_lead'],
                    ])>{{ $tile['title'] }}</h3>

                    @if($tile['chips'] !== [])
                        <ul class="mt-3 flex flex-wrap gap-1.5">
                            @foreach($tile['chips'] as $chip)
                                <li class="rounded-full border border-white/25 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white/90 sm:text-[11px]">
                                    {{ $chip }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($tile['is_wide'] && $tile['description'] !== '')
                        <p class="mt-3 max-w-xl text-sm leading-6 text-white/85">{{ $tile['description'] }}</p>
                    @endif

                    @if($tile['is_wide'] && $tile['cta_label'] !== '')
                        <span class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-[0.12em] text-amber-300 sm:text-sm">
                            {{ $tile['cta_label'] }}
                            <x-icon name="arrow-up-right" size="sm" class="transition group-hover:translate-x-0.5" />
                        </span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@endif
