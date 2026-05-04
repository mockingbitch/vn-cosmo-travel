@props([
    'title' => null,
])

@php
    $pageTitle = $title ?: __('Admin');
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
        <x-admin.sidebar />

        <div class="min-h-screen w-full">
            <x-admin.topbar :title="$pageTitle" />

            <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
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

