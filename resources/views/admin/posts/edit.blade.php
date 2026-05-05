@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <x-admin.card :title="__('ui.edit_post')" :subtitle="__('admin.posts.form_edit_subtitle')">
            <div class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                <span>{{ __('status') }}:</span>
                @if($post->status === \App\Models\Post::STATUS_ACTIVE)
                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ __('status.active') }}</span>
                @else
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                @endif
                <span class="text-xs text-slate-500">{{ __('post.admin.status_hint_list') }}</span>
            </div>

            <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="max-w-3xl space-y-4">
                @csrf
                @method('PUT')
                @include('admin.posts._form', ['post' => $post, 'categories' => $categories])
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        <x-icon name="save" size="sm" />
                        {{ __('save') }}
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <x-icon name="arrow-left" size="sm" />
                        {{ __('cancel') }}
                    </a>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
