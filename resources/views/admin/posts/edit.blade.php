@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <x-admin.card :title="__('ui.edit_post')" :subtitle="__('admin.posts.form_edit_subtitle')" heading="h1">
            <div class="mb-4 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                <span>{{ __('status') }}:</span>
                @if($post->status === \App\Models\Post::STATUS_ACTIVE)
                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ __('status.active') }}</span>
                @else
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                @endif
                <span class="text-xs text-slate-500">{{ __('post.admin.status_hint_list') }}</span>
            </div>

            <form method="POST" action="{{ route('admin.posts.update', $post) }}" class=" space-y-4">
                @csrf
                @method('PUT')
                @include('admin.posts._form', ['post' => $post, 'categories' => $categories])
                <x-admin.form-actions
                    submit-label="{{ __('save') }}"
                    submit-icon="save"
                    cancel-url="{{ route('admin.posts.index') }}"
                />
            </form>
        </x-admin.card>
    </div>
@endsection
