@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.settings.page_title') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('admin.settings.page_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data" class="mt-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('admin.settings.general.website') }}</div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.general.site_name') }}</span>
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
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.general.logo') }}</span>
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
                        <span class="text-xs font-semibold text-slate-700">{{ __('admin.settings.general.favicon') }}</span>
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
                    <div class="text-xs font-semibold text-slate-700">{{ __('admin.settings.general.current_logo') }}</div>
                    @if(!empty($settings['site.logo_path']))
                        <img class="mt-3 h-12 w-auto rounded bg-white p-2" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['site.logo_path']) }}" alt="logo" />
                    @else
                        <div class="mt-2 text-sm text-slate-500">{{ __('admin.settings.general.not_set') }}</div>
                    @endif
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs font-semibold text-slate-700">{{ __('admin.settings.general.current_favicon') }}</div>
                    @if(!empty($settings['site.favicon_path']))
                        <img class="mt-3 h-10 w-10 rounded bg-white p-2" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($settings['site.favicon_path']) }}" alt="favicon" />
                    @else
                        <div class="mt-2 text-sm text-slate-500">{{ __('admin.settings.general.not_set') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <x-admin.form-actions
            submit-label="{{ __('admin.settings.save_changes') }}"
            submit-icon="save"
        />
    </form>
@endsection
