@extends('admin.layouts.app')

@section('content')
    @if($errors->has('status'))
        <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
            {{ $errors->first('status') }}
        </div>
    @endif
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold tracking-tight">{{ __('ui.blog_posts') }}</h1>
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            <x-icon name="add" size="sm" />
            {{ __('ui.add_post') }}
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="grid items-end gap-3 border-b border-slate-100 bg-slate-50/70 px-4 py-4 lg:grid-cols-5">
            <label class="grid gap-1 lg:col-span-2">
                <span class="text-xs font-semibold text-slate-700">{{ __('ui.filter_keyword_label') }}</span>
                <input
                    type="text"
                    name="q"
                    value="{{ $filters['q'] ?? '' }}"
                    placeholder="{{ __('ui.filter_placeholder_posts') }}"
                    class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                />
            </label>
            <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-700">{{ __('status') }}</span>
                <select
                    name="status"
                    class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                >
                    <option value="">{{ __('all') }}</option>
                    <option value="{{ \App\Models\Post::STATUS_ACTIVE }}" @selected(($filters['status'] ?? '') === \App\Models\Post::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                    <option value="{{ \App\Models\Post::STATUS_DISABLED }}" @selected(($filters['status'] ?? '') === \App\Models\Post::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                </select>
            </label>
            <label class="grid gap-1">
                <span class="text-xs font-semibold text-slate-700">{{ __('category') }}</span>
                <select
                    name="category_id"
                    class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                >
                    <option value="">{{ __('all') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) ($filters['category_id'] ?? '') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white hover:bg-slate-800">
                    <x-icon name="search" size="sm" />
                    {{ __('filter') }}
                </button>
                <a href="{{ route('admin.posts.index') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    <x-icon name="close" size="sm" />
                    {{ __('ui.clear_filter') }}
                </a>
            </div>
        </form>
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                <tr>
                    <th class="px-4 py-3">{{ __('title') }}</th>
                    <th class="px-4 py-3">{{ __('category') }}</th>
                    <th class="px-4 py-3">{{ __('status') }}</th>
                    @if(auth()->user()->canManageUsers())
                        <th class="px-4 py-3">{{ __('audit.created_by') }}</th>
                        <th class="px-4 py-3">{{ __('audit.updated_by') }}</th>
                    @endif
                    <th class="px-4 py-3 text-right">{{ __('actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($posts as $post)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if($post->thumbnailMedia)
                                    <img
                                        class="h-10 w-10 rounded-xl object-cover ring-1 ring-slate-200"
                                        src="{{ $post->thumbnailMedia->url() }}"
                                        alt="{{ $post->thumbnailMedia->alt_text ?? '' }}"
                                        loading="lazy"
                                    />
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-slate-100 ring-1 ring-slate-200"></div>
                                @endif
                                <div class="min-w-0">
                                    <div class="truncate font-medium text-slate-900">{{ $post->title }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $post->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 align-middle">
                            <form method="post" action="{{ route('admin.posts.update-status', $post) }}" class="inline-block min-w-[9rem]">
                                @csrf
                                @method('PATCH')
                                @if($posts->currentPage() > 1)
                                    <input type="hidden" name="page" value="{{ $posts->currentPage() }}">
                                @endif
                                @if(!empty($filters['q']))
                                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                @endif
                                @if(!empty($filters['status']))
                                    <input type="hidden" name="status" value="{{ $filters['status'] }}">
                                @endif
                                @if(!empty($filters['category_id']))
                                    <input type="hidden" name="category_id" value="{{ $filters['category_id'] }}">
                                @endif
                                <label class="sr-only" for="post-status-{{ $post->id }}">{{ __('status') }}</label>
                                <select
                                    id="post-status-{{ $post->id }}"
                                    name="status"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-medium text-slate-800 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                    onchange="this.form.requestSubmit()"
                                >
                                    <option value="{{ \App\Models\Post::STATUS_ACTIVE }}" @selected($post->status === \App\Models\Post::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                                    <option value="{{ \App\Models\Post::STATUS_DISABLED }}" @selected($post->status === \App\Models\Post::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                                </select>
                            </form>
                        </td>
                        @if(auth()->user()->canManageUsers())
                            <td class="px-4 py-3 text-slate-600">{{ $post->creator?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $post->updatedBy?->name ?? '—' }}</td>
                        @endif
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <x-admin.action-icon
                                    :href="route('admin.posts.edit', $post)"
                                    icon="pencil"
                                    :title="__('edit')"
                                />
                                <x-admin.confirm-delete
                                    :delete-url="route('admin.posts.destroy', $post)"
                                    :message="__('confirm.delete_post')"
                                >
                                    <x-admin.action-icon icon="trash" variant="danger" :title="__('delete')" />
                                </x-admin.confirm-delete>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
@endsection
