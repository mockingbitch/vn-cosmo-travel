@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <x-admin.card :title="__('Edit post')" :subtitle="__('admin.posts.form_edit_subtitle')">
            <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="max-w-3xl space-y-4">
                @csrf
                @method('PUT')
                @include('admin.posts._form', ['post' => $post, 'categories' => $categories])
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Save') }}</button>
                    <a href="{{ route('admin.posts.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</a>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
