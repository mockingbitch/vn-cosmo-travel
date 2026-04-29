@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold tracking-tight">{{ __('Blog posts') }}</h1>
        <a href="{{ route('admin.posts.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Add post') }}</a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                <tr>
                    <th class="px-4 py-3">{{ __('Title') }}</th>
                    <th class="px-4 py-3">{{ __('Category') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
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
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="font-semibold text-slate-900 hover:underline">{{ __('Edit') }}</a>
                            <x-admin.confirm-delete
                                :delete-url="route('admin.posts.destroy', $post)"
                                :message="__('confirm.delete_post')"
                            >
                                <button type="button" class="ml-3 font-semibold text-rose-600 hover:underline">
                                    {{ __('Delete') }}
                                </button>
                            </x-admin.confirm-delete>
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
