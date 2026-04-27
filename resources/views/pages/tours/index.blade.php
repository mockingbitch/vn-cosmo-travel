@extends('layouts.app')

@section('content')
    <section class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ __('Tours') }}</h1>
                    <p class="mt-2 text-sm text-slate-600">{{ __('Tours index intro') }}</p>
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
                        <div class="text-sm font-semibold text-slate-900">{{ __('Filters') }}</div>
                        <a href="{{ route('tours.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">{{ __('Reset') }}</a>
                    </div>

                    <div class="mt-4 grid gap-4">
                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Destination') }}</span>
                            <select
                                name="destination"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('Any') }}</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->slug }}" @selected(($filters['destination'] ?? '') === $destination->slug)>
                                        {{ $destination->localizedName() }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Duration') }}</span>
                            <select
                                name="duration"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="">{{ __('Any') }}</option>
                                <option value="1-3" @selected(($filters['duration'] ?? '') === '1-3')>{{ __('1–3 days') }}</option>
                                <option value="4-7" @selected(($filters['duration'] ?? '') === '4-7')>{{ __('4–7 days') }}</option>
                                <option value="8+" @selected(($filters['duration'] ?? '') === '8+')>{{ __('8+ days') }}</option>
                            </select>
                        </label>

                        <div class="grid grid-cols-2 gap-3">
                            <x-input
                                label="{{ __('Min price (VND)') }}"
                                name="min_price"
                                placeholder="0"
                                :value="$filters['min_price'] ?? null"
                                inputmode="numeric"
                            />
                            <x-input
                                label="{{ __('Max price (VND)') }}"
                                name="max_price"
                                placeholder="20000000"
                                :value="$filters['max_price'] ?? null"
                                inputmode="numeric"
                            />
                        </div>

                        <label class="block">
                            <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Sort') }}</span>
                            <select
                                name="sort"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >
                                <option value="popular" @selected(($filters['sort'] ?? 'popular') === 'popular')>{{ __('Recommended') }}</option>
                                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>{{ __('Price: Low to high') }}</option>
                                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>{{ __('Price: High to low') }}</option>
                                <option value="duration_asc" @selected(($filters['sort'] ?? '') === 'duration_asc')>{{ __('Duration: Short to long') }}</option>
                            </select>
                        </label>

                        <x-button type="submit" variant="primary" class="w-full justify-center">{{ __('Apply') }}</x-button>
                    </div>
                </form>
            </aside>

            <div class="lg:col-span-9">
                @if($tours->count() === 0)
                    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                        <div class="text-base font-semibold text-slate-900">{{ __('No tours found') }}</div>
                        <p class="mt-2 text-sm text-slate-600">{{ __('Try relaxing filters') }}</p>
                        <div class="mt-5">
                            <x-button href="{{ route('tours.index') }}" variant="secondary">{{ __('Reset filters') }}</x-button>
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

