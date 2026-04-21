<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Admin title suffix', ['app' => config('app.name')]) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen lg:flex">
        <aside class="border-b border-slate-200 bg-white lg:min-h-screen lg:w-64 lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between gap-3 px-4 py-4 lg:px-6">
                <a href="{{ route('admin.tours.index') }}" class="text-sm font-semibold">{{ __('Admin') }}</a>
                <a class="text-xs font-semibold text-slate-500 hover:text-slate-900" href="{{ route('home') }}">{{ __('View site') }}</a>
            </div>

            <nav class="grid gap-1 px-2 pb-4 text-sm font-medium text-slate-700 lg:px-4">
                <a class="rounded-xl px-3 py-2 hover:bg-slate-50" href="{{ route('admin.settings.edit') }}">{{ __('Settings') }}</a>
                <a class="rounded-xl px-3 py-2 hover:bg-slate-50" href="{{ route('admin.banners.index') }}">{{ __('Banners') }}</a>
                <a class="rounded-xl px-3 py-2 hover:bg-slate-50" href="{{ route('admin.tours.index') }}">{{ __('Tours') }}</a>
                <a class="rounded-xl px-3 py-2 hover:bg-slate-50" href="{{ route('admin.posts.index') }}">{{ __('Blog') }}</a>
                <a class="rounded-xl px-3 py-2 hover:bg-slate-50" href="{{ route('admin.destinations.index') }}">{{ __('Destinations') }}</a>
                <a class="rounded-xl px-3 py-2 hover:bg-slate-50" href="{{ route('admin.bookings.index') }}">{{ __('Bookings') }}</a>

                <form class="mt-3 px-3" method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-left text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        {{ __('Sign out') }}
                    </button>
                </form>
            </nav>
        </aside>

        <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6">
        @if(session('status'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
        </main>
    </div>
</body>
</html>
