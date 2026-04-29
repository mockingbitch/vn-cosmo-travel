@extends('admin.layouts.app')

@section('content')
    @php
        /** @var array<string, mixed> $homeWhyForm */
        $homeWhyForm = old('home_why', $settings['content.home_why'] ?? []);
        $homeWhyLocales = ['vi' => __('Vietnamese'), 'en' => __('English')];
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Settings') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Manage website configuration') }}</p>
        </div>
        <a
            href="{{ route('home') }}#why-us"
            target="_blank"
            rel="noopener"
            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            {{ __('Preview on site') }}
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.homeWhy.update') }}" class="mt-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('Home why section') }}</div>
            <p class="mt-1 text-xs text-slate-500">{{ __('Home why section help') }}</p>
            <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800 ring-1 ring-amber-200">{{ __('Leave a field empty to use the default text from translations.') }}</p>

            @foreach($homeWhyLocales as $loc => $label)
                @php
                    /** @var array<string, mixed> $b */
                    $b = is_array($homeWhyForm[$loc] ?? null) ? $homeWhyForm[$loc] : [];
                @endphp
                <div class="mt-6 rounded-xl border border-slate-100 bg-slate-50/80 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $label }}</div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <label class="grid gap-1 sm:col-span-2">
                            <span class="text-xs font-semibold text-slate-700">{{ __('Section title') }}</span>
                            <input
                                type="text"
                                name="home_why[{{ $loc }}][title]"
                                value="{{ $b['title'] ?? '' }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            />
                            @error('home_why.'.$loc.'.title')
                                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                            @enderror
                        </label>

                        <label class="grid gap-1 sm:col-span-2">
                            <span class="text-xs font-semibold text-slate-700">{{ __('Section subtitle') }}</span>
                            <textarea
                                name="home_why[{{ $loc }}][subtitle]"
                                rows="2"
                                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                            >{{ $b['subtitle'] ?? '' }}</textarea>
                            @error('home_why.'.$loc.'.subtitle')
                                <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                            @enderror
                        </label>
                    </div>

                    @for($i = 0; $i < 4; $i++)
                        @php
                            /** @var array<string, mixed> $it */
                            $it = is_array(($b['items'] ?? [])[$i] ?? null) ? ($b['items'][$i]) : [];
                        @endphp
                        <div class="mt-4 rounded-xl border border-dashed border-slate-200 bg-white p-4">
                            <div class="text-xs font-semibold text-slate-700">{{ __('Why card :number', ['number' => $i + 1]) }}</div>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <label class="grid gap-1 sm:col-span-2">
                                    <span class="text-xs font-semibold text-slate-600">{{ __('Card title') }}</span>
                                    <input
                                        type="text"
                                        name="home_why[{{ $loc }}][items][{{ $i }}][title]"
                                        value="{{ $it['title'] ?? '' }}"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                    />
                                    @error('home_why.'.$loc.'.items.'.$i.'.title')
                                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                                    @enderror
                                </label>
                                <label class="grid gap-1 sm:col-span-2">
                                    <span class="text-xs font-semibold text-slate-600">{{ __('Card description') }}</span>
                                    <textarea
                                        name="home_why[{{ $loc }}][items][{{ $i }}][desc]"
                                        rows="2"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                    >{{ $it['desc'] ?? '' }}</textarea>
                                    @error('home_why.'.$loc.'.items.'.$i.'.desc')
                                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                                    @enderror
                                </label>
                            </div>
                        </div>
                    @endfor
                </div>
            @endforeach
        </div>

        <div class="mt-10 flex items-center justify-end gap-3 pt-2 sm:pt-3">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
            >
                {{ __('Save changes') }}
            </button>
        </div>
    </form>
@endsection
