@extends('admin.layouts.app')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <x-admin.card class="p-5">
                    <div class="text-xs font-semibold text-slate-500">{{ __('Total Posts') }}</div>
                    <div class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ number_format($stats['posts'] ?? 0) }}</div>
                    <div class="mt-2 text-xs font-semibold text-emerald-700">{{ __('Healthy') }}</div>
                </x-admin.card>
                <x-admin.card class="p-5">
                    <div class="text-xs font-semibold text-slate-500">{{ __('Total Banners') }}</div>
                    <div class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ number_format($stats['banners'] ?? 0) }}</div>
                    <div class="mt-2 text-xs font-semibold text-slate-600">{{ __('In rotation') }}</div>
                </x-admin.card>
                <x-admin.card class="p-5">
                    <div class="text-xs font-semibold text-slate-500">{{ __('Visitors') }}</div>
                    <div class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">{{ number_format($stats['visitors'] ?? 0) }}</div>
                    <div class="mt-2 text-xs font-semibold text-slate-600">{{ __('Mock data') }}</div>
                </x-admin.card>
                <x-admin.card class="p-5">
                    <div class="text-xs font-semibold text-slate-500">{{ __('Revenue') }}</div>
                    <div class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">
                        {{ number_format(($stats['revenue'] ?? 0) / 1000000, 1) }}M
                    </div>
                    <div class="mt-2 text-xs font-semibold text-slate-600">{{ __('Mock data') }}</div>
                </x-admin.card>
            </div>

            <x-admin.card class="mt-6" :title="__('Recent posts')" :subtitle="__('Latest content updates')">
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                            <tr>
                                <th class="px-4 py-3">{{ __('Title') }}</th>
                                <th class="px-4 py-3">{{ __('Status') }}</th>
                                <th class="px-4 py-3">{{ __('Date') }}</th>
                                <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse($recentPosts as $post)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-900">{{ $post->title }}</div>
                                        <div class="mt-1 text-xs font-medium text-slate-500">/{{ $post->slug }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">{{ __('Live') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-700">
                                        {{ optional($post->created_at)?->locale(app()->getLocale())->isoFormat('LL') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <x-admin.button size="sm" variant="secondary" :href="route('admin.posts.edit', $post)">{{ __('Edit') }}</x-admin.button>
                                            <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <x-admin.button size="sm" variant="ghost" type="submit" class="text-rose-700 hover:bg-rose-50">{{ __('Delete') }}</x-admin.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-12 text-center text-slate-500">{{ __('No posts yet') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
        </div>

        <div class="grid gap-6">
            <x-admin.card :title="__('Quick actions')" :subtitle="__('Shortcuts to common tasks')">
                <div class="grid gap-3">
                    <x-admin.button :href="route('admin.posts.create')" variant="primary">{{ __('Add new post') }}</x-admin.button>
                    <x-admin.button :href="route('admin.settings.edit')" variant="secondary">{{ __('Update settings') }}</x-admin.button>
                    <x-admin.button :href="route('admin.banners.create')" variant="secondary">{{ __('Create hero banner') }}</x-admin.button>
                </div>
            </x-admin.card>

            <x-admin.card :title="__('Activity')" :subtitle="__('What happened recently')">
                <div class="grid gap-4">
                    <div class="flex gap-3">
                        <div class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
                        <div class="text-sm text-slate-700">
                            <span class="font-semibold text-slate-900">{{ __('System') }}</span>
                            {{ __('Dashboard UI installed') }}
                            <div class="mt-1 text-xs text-slate-500">{{ now()->subMinutes(12)->diffForHumans() }}</div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="mt-1 h-2.5 w-2.5 rounded-full bg-slate-400"></div>
                        <div class="text-sm text-slate-700">
                            <span class="font-semibold text-slate-900">{{ __('Next') }}</span>
                            {{ __('Add media library + blog publishing workflow') }}
                            <div class="mt-1 text-xs text-slate-500">{{ __('Planned') }}</div>
                        </div>
                    </div>
                </div>
            </x-admin.card>
        </div>
    </div>
@endsection

