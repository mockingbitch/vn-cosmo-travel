@extends('admin.layouts.app')

@section('content')
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Settings') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('Manage website configuration') }}</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.contact.update') }}" class="mt-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('Contact') }}</div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Email') }}</span>
                    <input
                        type="email"
                        name="contact_email"
                        value="{{ old('contact_email', $settings['contact.email'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('contact_email')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Phone') }}</span>
                    <input
                        type="text"
                        name="contact_phone"
                        value="{{ old('contact_phone', $settings['contact.phone'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('contact_phone')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>
            </div>

            <div class="mt-4 grid gap-4">
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Address') }}</span>
                    <input
                        type="text"
                        name="contact_address"
                        value="{{ old('contact_address', $settings['contact.address'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('contact_address')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Google map iframe') }}</span>
                    <textarea
                        name="google_map_iframe"
                        rows="5"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >{{ old('google_map_iframe', $settings['contact.map_iframe'] ?? '') }}</textarea>
                    @error('google_map_iframe')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>
            </div>
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
