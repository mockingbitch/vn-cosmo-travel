<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin login title', ['app' => config('app.name')]) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <main class="mx-auto flex min-h-screen max-w-md items-center px-4 py-12">
        <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-lg font-semibold">{{ __('Admin login heading') }}</div>
            <div class="mt-1 text-sm text-slate-600">{{ __('Admin login subheading') }}</div>

            <form class="mt-6 grid gap-4" method="POST" action="{{ route('admin.login.store') }}">
                @csrf

                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Email') }}</span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('email')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('Password') }}</span>
                    <input
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                    @error('password')
                        <div class="text-xs font-medium text-rose-700">{{ $message }}</div>
                    @enderror
                </label>

                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400/60"
                    />
                    <span>{{ __('Remember me') }}</span>
                </label>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
                >
                    {{ __('Sign in') }}
                </button>
            </form>
        </div>
    </main>
</body>
</html>

