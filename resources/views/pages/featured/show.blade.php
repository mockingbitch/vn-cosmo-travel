@extends('layouts.app')

@section('content')
    <section class="relative isolate overflow-hidden bg-slate-900">
        @if($tile['image_url'] !== '')
            <img
                src="{{ $tile['image_url'] }}"
                alt="{{ $tile['title'] }}"
                class="absolute inset-0 h-full w-full object-cover"
                decoding="async"
            />
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/60 to-slate-950/30"></div>

        <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-white/70" aria-label="{{ __('featured.breadcrumb') }}">
                <a href="{{ route('home') }}" class="hover:text-white">{{ __('nav.header.home') }}</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('featured') }}" class="hover:text-white">{{ __('featured.index.title') }}</a>
            </nav>

            @if($tile['eyebrow'] !== '')
                <span class="mt-4 inline-flex rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-white backdrop-blur">
                    {{ $tile['eyebrow'] }}
                </span>
            @endif

            <h1 class="mt-3 font-serif text-3xl text-white sm:text-4xl lg:text-5xl">{{ $tile['title'] }}</h1>

            @if($tile['chips'] !== [])
                <ul class="mt-4 flex flex-wrap gap-1.5">
                    @foreach($tile['chips'] as $chip)
                        <li class="rounded-full border border-white/25 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white/90 sm:text-[11px]">
                            {{ $chip }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($tile['description'] !== '')
                <p class="mt-5 max-w-2xl text-sm leading-7 text-white/85 sm:text-base">{{ $tile['description'] }}</p>
            @endif
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <x-section-title
                :title="__('featured.tours_title', ['tile' => $tile['title']])"
                :subtitle="$usesFallback ? __('featured.tours_subtitle_fallback') : __('featured.tours_subtitle')"
            />
            <div class="shrink-0">
                <x-button href="{{ route('tours.index') }}" variant="secondary">
                    {{ __('ui.view_all_tours') }}
                    <x-icon name="chevron-right" size="sm" />
                </x-button>
            </div>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($tours as $vm)
                <x-tour-card :vm="$vm" />
            @empty
                <div class="sm:col-span-2 lg:col-span-3">
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center">
                        <p class="text-sm font-medium text-slate-900">{{ __('featured.empty_title') }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ __('featured.empty_subtitle') }}</p>
                        <div class="mt-5 flex justify-center">
                            <x-button href="{{ route('tours.index') }}" variant="primary">
                                {{ __('ui.view_all_tours') }}
                                <x-icon name="chevron-right" size="sm" />
                            </x-button>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
@endsection
