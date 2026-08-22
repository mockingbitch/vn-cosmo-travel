@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <x-admin.card :title="__('ui.new_post')" :subtitle="__('admin.posts.form_create_subtitle')" heading="h1">
            <form method="POST" action="{{ route('admin.posts.store') }}" class=" space-y-4">
                @csrf
                @include('admin.posts._form', ['post' => null, 'categories' => $categories])
                <x-admin.form-actions
                    submit-label="{{ __('create') }}"
                    submit-icon="add"
                    cancel-url="{{ route('admin.posts.index') }}"
                />
            </form>
        </x-admin.card>
    </div>
@endsection
