@extends('admin.layouts.app')

@section('content')
    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Settings') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Manage website configuration') }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="mt-6 grid gap-8">
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

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-900">{{ __('Social links') }}</div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">Facebook</span>
                    <input
                        type="url"
                        name="facebook"
                        value="{{ old('facebook', $settings['social.facebook'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('facebook')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">Instagram</span>
                    <input
                        type="url"
                        name="instagram"
                        value="{{ old('instagram', $settings['social.instagram'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('instagram')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">YouTube</span>
                    <input
                        type="url"
                        name="youtube"
                        value="{{ old('youtube', $settings['social.youtube'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('youtube')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">TikTok</span>
                    <input
                        type="url"
                        name="tiktok"
                        value="{{ old('tiktok', $settings['social.tiktok'] ?? '') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('tiktok')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
            >
                {{ __('Save changes') }}
            </button>
        </div>
    </form>
@endsection

