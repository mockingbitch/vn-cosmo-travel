@extends('admin.layouts.app')

@section('content')
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Settings') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('Manage website configuration') }}</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data" class="mt-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('Website') }}</div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Site name') }}</span>
                    <input
                        type="text"
                        name="site_name"
                        value="{{ old('site_name', $settings['site.name'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('site_name')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="grid gap-1">
                        <span class="text-xs font-semibold text-slate-700">{{ __('Logo') }}</span>
                        <input
                            type="file"
                            name="logo"
                            accept="image/*"
                            class="block w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800"
                        />
                        @error('logo')
                            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                        @enderror
                    </label>

                    <label class="grid gap-1">
                        <span class="text-xs font-semibold text-slate-700">{{ __('Favicon') }}</span>
                        <input
                            type="file"
                            name="favicon"
                            accept="image/*"
                            class="block w-full text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-900 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800"
                        />
                        @error('favicon')
                            <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                        @enderror
                    </label>
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold text-slate-700">{{ __('Current logo') }}</div>
                    @if(!empty($settings['site.logo_path']))
                        <img class="mt-3 h-12 w-auto rounded bg-white p-2" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['site.logo_path']) }}" alt="logo" />
                    @else
                        <div class="mt-2 text-sm text-slate-500">{{ __('Not set') }}</div>
                    @endif
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold text-slate-700">{{ __('Current favicon') }}</div>
                    @if(!empty($settings['site.favicon_path']))
                        <img class="mt-3 h-10 w-10 rounded bg-white p-2" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['site.favicon_path']) }}" alt="favicon" />
                    @else
                        <div class="mt-2 text-sm text-slate-500">{{ __('Not set') }}</div>
                    @endif
                </div>
            </div>
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
