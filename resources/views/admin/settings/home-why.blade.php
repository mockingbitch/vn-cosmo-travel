@extends('admin.layouts.app')

@section('content')
    @php
        /** @var array<string, mixed> $homeWhyForm */
        $homeWhyForm = old('home_why', $settings['content.home_why'] ?? []);
        $homeWhyLocales = ['vi' => __('vietnamese'), 'en' => __('english')];
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
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700"
        >
            <x-icon name="external-link" size="sm" />
            {{ __('ui.preview_on_site') }}
        </a>
    </div>

    <form method="POST" action="{{ route('admin.settings.homeWhy.update') }}" class="mt-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('admin.settings.home_why.section') }}</div>
            <p class="mt-1 text-xs text-slate-500">{{ __('admin.settings.home_why.help') }}</p>
            <p class="mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800 ring-1 ring-amber-200">{{ __('admin.settings.translation_fallback_help') }}</p>

            @foreach($homeWhyLocales as $loc => $label)
                @php
                    /** @var array<string, mixed> $b */
                    $b = is_array($homeWhyForm[$loc] ?? null) ? $homeWhyForm[$loc] : [];
                @endphp
                <div class="mt-6 rounded-xl border border-slate-100 bg-slate-50/80 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">{{ $label }}</div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <label class="grid gap-1 sm:col-span-2">
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.home_why.section_title') }}</span>
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
                            <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.home_why.section_subtitle') }}</span>
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
                            <div class="text-xs font-semibold text-slate-700">{{ __('admin.settings.home_why.card', ['number' => $i + 1]) }}</div>
                            <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                <label class="grid gap-1 sm:col-span-2">
                                    <span class="text-xs font-semibold text-slate-600">{{ __('admin.settings.home_why.card_title') }}</span>
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
                                    <span class="text-xs font-semibold text-slate-600">{{ __('admin.settings.home_why.card_description') }}</span>
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
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
            >
                <x-icon name="save" size="sm" />
                {{ __('Save changes') }}
            </button>
        </div>
    </form>
@endsection
