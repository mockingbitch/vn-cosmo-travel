@extends('layouts.app')

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ __('tours') }}</h1>
                    <p class="mt-2 text-sm text-slate-600">{{ __('ui.tours_index_intro') }}</p>
                </div>
                <div class="text-sm text-slate-600">
                    <span class="font-semibold text-slate-900">{{ $tours->total() }}</span> {{ __('results') }}
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-12">
            <aside class="lg:col-span-3">
                <form method="GET" action="{{ route('tours.index') }}" class="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold text-slate-900">{{ __('filters') }}</div>
                        <a href="{{ route('tours.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">{{ __('reset') }}</a>
                    </div>

                    <div class="mt-4 grid gap-4">
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('destination') }}</span>
                            <select
                                name="destination"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('any') }}</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->slug }}" @selected(($filters['destination'] ?? '') === $destination->slug)>
                                        {{ $destination->localizedName() }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('duration') }}</span>
                            <select
                                name="duration"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('any') }}</option>
                                <option value="1-3" @selected(($filters['duration'] ?? '') === '1-3')>{{ __('ui.13_days') }}</option>
                                <option value="4-7" @selected(($filters['duration'] ?? '') === '4-7')>{{ __('ui.47_days') }}</option>
                                <option value="8+" @selected(($filters['duration'] ?? '') === '8+')>{{ __('ui.8_days') }}</option>
                            </select>
                        </label>

                        <div class="grid grid-cols-2 items-end gap-x-3 gap-y-2">
                            <div class="min-w-0">
                                <x-input
                                    compact
                                    label="{{ __('ui.min_price_vnd') }}"
                                    name="min_price"
                                    :placeholder="__('placeholder.min_price')"
                                    :value="$filters['min_price'] ?? null"
                                    inputmode="numeric"
                                />
                            </div>
                            <div class="min-w-0">
                                <x-input
                                    compact
                                    label="{{ __('ui.max_price_vnd') }}"
                                    name="max_price"
                                    :placeholder="__('placeholder.max_price')"
                                    :value="$filters['max_price'] ?? null"
                                    inputmode="numeric"
                                />
                            </div>
                        </div>

                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('sort') }}</span>
                            <select
                                name="sort"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="popular" @selected(($filters['sort'] ?? 'popular') === 'popular')>{{ __('recommended') }}</option>
                                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>{{ __('ui.price_low_to_high') }}</option>
                                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>{{ __('ui.price_high_to_low') }}</option>
                                <option value="duration_asc" @selected(($filters['sort'] ?? '') === 'duration_asc')>{{ __('ui.duration_short_to_long') }}</option>
                            </select>
                        </label>

                        <x-button type="submit" variant="primary" class="w-full justify-center">
                            <x-icon name="check" size="sm" />
                            {{ __('apply') }}
                        </x-button>
                    </div>
                </form>
            </aside>

            <div class="lg:col-span-9">
                @if($tours->count() === 0)
                    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                        <div class="text-base font-semibold text-slate-900">{{ __('ui.no_tours_found') }}</div>
                        <p class="mt-2 text-sm text-slate-600">{{ __('ui.try_relaxing_filters') }}</p>
                        <div class="mt-5">
                            <x-button href="{{ route('tours.index') }}" variant="secondary">
                                <x-icon name="arrow-left" size="sm" />
                                {{ __('ui.reset_filters') }}
                            </x-button>
                        </div>
                    </div>
                @else
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($tours as $vm)
                            <x-tour-card :vm="$vm" />
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $tours->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

