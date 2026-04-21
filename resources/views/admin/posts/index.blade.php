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
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $post->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $post->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="font-semibold text-slate-900 hover:underline">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm({{ \Illuminate\Support\Js::from(__('confirm.delete_post')) }});">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-semibold text-rose-600 hover:underline">{{ __('Delete') }}</button>
                            </form>
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
