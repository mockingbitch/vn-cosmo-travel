@extends('admin.layouts.app')

@section('content')
    <div class="grid gap-8">
        {{-- KPI: chỉ bài viết, tour, booking (số liệu thật từ DB) --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <div class="absolute right-4 top-4 rounded-xl bg-indigo-50 p-2 text-indigo-600 ring-1 ring-indigo-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 11.25h7.5m-7.5 3h7.5m-7.5 3h4.5M6.75 2.25H10.5A2.25 2.25 0 0 1 12.75 4.5v2.25A2.25 2.25 0 0 0 15 9h2.25A2.25 2.25 0 0 1 19.5 11.25v9A2.25 2.25 0 0 1 17.25 22.5h-9A2.25 2.25 0 0 1 6 20.25V4.5A2.25 2.25 0 0 1 8.25 2.25Z" />
                    </svg>
                </div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Total Posts') }}</div>
                <div class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">{{ number_format($stats['posts'] ?? 0) }}</div>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ __('dashboard.stats.posts_hint') }}</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <div class="absolute right-4 top-4 rounded-xl bg-emerald-50 p-2 text-emerald-600 ring-1 ring-emerald-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Total tours') }}</div>
                <div class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">{{ number_format($stats['tours'] ?? 0) }}</div>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ __('dashboard.stats.tours_hint') }}</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <div class="absolute right-4 top-4 rounded-xl bg-violet-50 p-2 text-violet-600 ring-1 ring-violet-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                    </svg>
                </div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Total bookings') }}</div>
                <div class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">{{ number_format($stats['bookings'] ?? 0) }}</div>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ __('dashboard.stats.bookings_hint') }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <x-admin.card :title="__('Recent posts')" :subtitle="__('Latest content updates')">
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
                                    <tr class="hover:bg-slate-50/80">
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
                                                <x-admin.action-icon
                                                    :href="route('admin.posts.edit', $post)"
                                                    icon="pencil"
                                                    :title="__('Edit')"
                                                />
                                                <x-admin.confirm-delete
                                                    :delete-url="route('admin.posts.destroy', $post)"
                                                    :message="__('Are you sure?')"
                                                >
                                                    <x-admin.action-icon icon="trash" variant="danger" :title="__('Delete')" />
                                                </x-admin.confirm-delete>
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

            <div>
                <x-admin.card :title="__('Quick actions')" :subtitle="__('Shortcuts to common tasks')">
                    <div class="grid gap-3">
                        <x-admin.button :href="route('admin.posts.create')" variant="primary">{{ __('Add new post') }}</x-admin.button>
                        <x-admin.button :href="route('admin.tours.create')" variant="secondary">{{ __('Add tour') }}</x-admin.button>
                        <x-admin.button :href="route('admin.bookings.index')" variant="secondary">{{ __('Bookings') }}</x-admin.button>
                        @if(auth()->user()->canManageUsers())
                            <x-admin.button :href="route('admin.settings.edit')" variant="secondary">{{ __('Update settings') }}</x-admin.button>
                        @endif
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>
@endsection
