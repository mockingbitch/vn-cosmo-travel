@extends('admin.layouts.app')

@section('content')
    <div class="grid gap-8">
        {{-- KPI: chỉ bài viết, tour, booking (số liệu thật từ DB) --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <div class="absolute right-4 top-4 rounded-xl bg-indigo-50 p-2 text-indigo-600 ring-1 ring-indigo-100">
                    <x-icon name="document" size="md" />
                </div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Total Posts') }}</div>
                <div class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">{{ number_format($stats['posts'] ?? 0) }}</div>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ __('dashboard.stats.posts_hint') }}</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <div class="absolute right-4 top-4 rounded-xl bg-emerald-50 p-2 text-emerald-600 ring-1 ring-emerald-100">
                    <x-icon name="map" size="md" />
                </div>
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ __('Total tours') }}</div>
                <div class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">{{ number_format($stats['tours'] ?? 0) }}</div>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">{{ __('dashboard.stats.tours_hint') }}</p>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm ring-1 ring-slate-100">
                <div class="absolute right-4 top-4 rounded-xl bg-violet-50 p-2 text-violet-600 ring-1 ring-violet-100">
                    <x-icon name="inbox" size="md" />
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
                        <x-admin.button :href="route('admin.posts.create')" variant="primary">
                            <x-icon name="add" size="sm" />
                            {{ __('Add new post') }}
                        </x-admin.button>
                        <x-admin.button :href="route('admin.tours.create')" variant="secondary">
                            <x-icon name="add" size="sm" />
                            {{ __('Add tour') }}
                        </x-admin.button>
                        <x-admin.button :href="route('admin.bookings.index')" variant="secondary">
                            <x-icon name="book" size="sm" />
                            {{ __('Bookings') }}
                        </x-admin.button>
                        @if(auth()->user()->canManageUsers())
                            <x-admin.button :href="route('admin.settings.edit')" variant="secondary">
                                <x-icon name="cog" size="sm" />
                                {{ __('Update settings') }}
                            </x-admin.button>
                        @endif
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>
@endsection
