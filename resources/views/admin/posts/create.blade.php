@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <x-admin.card :title="__('New post')" :subtitle="__('admin.posts.form_create_subtitle')">
            <form method="POST" action="{{ route('admin.posts.store') }}" class="max-w-3xl space-y-4">
                @csrf
                @include('admin.posts._form', ['post' => null, 'categories' => $categories])
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        <x-icon name="add" size="sm" />
                        {{ __('Create') }}
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <x-icon name="arrow-left" size="sm" />
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
