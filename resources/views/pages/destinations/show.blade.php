@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden bg-slate-900">
        <div class="absolute inset-0 opacity-40">
            <img
                src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=2400&q=80"
                alt="{{ $destination->localizedName() }}"
                class="h-full w-full object-cover"
                loading="lazy"
            />
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <div class="text-sm font-semibold text-white/70">{{ __('Destination') }}</div>
                <h1 class="mt-2 text-4xl font-semibold tracking-tight text-white sm:text-5xl">{{ $destination->localizedName() }}</h1>
                <p class="mt-4 text-base leading-7 text-white/85 sm:text-lg">
                    {{ $destination->description }}
                </p>
                <div class="mt-7">
                    <x-button href="{{ route('tours.index', ['destination' => $destination->slug]) }}" variant="primary" class="bg-white text-slate-900 hover:bg-white/90">
                        {{ __('destinations.view_tours', ['name' => $destination->localizedName()]) }}
                    </x-button>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-6">
            <h2 class="text-xl font-semibold tracking-tight text-slate-900">{{ __('destinations.tours_heading') }}</h2>
            <div class="text-sm text-slate-600">
                <span class="font-semibold text-slate-900">{{ $tours->total() }}</span> {{ __('results') }}
            </div>
        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($tours as $vm)
                <x-tour-card :vm="$vm" />
            @endforeach
        </div>

        <div class="mt-10">
            {{ $tours->links() }}
        </div>
    </section>
@endsection

