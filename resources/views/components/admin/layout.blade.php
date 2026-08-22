@props([
    'title' => null,
])

@php
    $pageTitle = $title ?: __('admin');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }} — {{ config('app.name') }}</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div
        x-data="{
            sidebarOpen: false,
            sidebarCollapsed: false,
            profileOpen: false,
            toast: {{ session()->has('status') ? 'true' : 'false' }},
        }"
        x-init="sidebarCollapsed = localStorage.getItem('admin.sidebarCollapsed') === '1'"
        @keydown.escape.window="sidebarOpen = false; profileOpen = false"
        class="min-h-screen lg:flex"
    >
        <a
            href="#admin-content"
            class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-xl focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-slate-900 focus:shadow-lg focus:ring-2 focus:ring-indigo-500"
        >
            {{ __('ui.skip_to_content') }}
        </a>

        <x-admin.sidebar />

        <div class="min-h-screen w-full">
            <x-admin.topbar :title="$pageTitle" />

            <main id="admin-content" tabindex="-1" class="mx-auto w-full max-w-[96rem] px-4 py-6 sm:px-6 lg:px-8">
                @if(session('status'))
                    <div
                        x-show="toast"
                        x-transition.opacity
                        role="status"
                        aria-live="polite"
                        class="mb-6 flex items-start justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm"
                    >
                        <div class="font-medium">{{ session('status') }}</div>
                        <button
                            type="button"
                            class="rounded-lg p-1 text-emerald-900/70 hover:bg-emerald-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2"
                            @click="toast = false"
                            aria-label="{{ __('a11y.dismiss_notification') }}"
                        >
                            <x-icon name="close" size="md" />
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>

