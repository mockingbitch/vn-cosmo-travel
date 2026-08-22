@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto flex w-full flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $pageTitle }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('admin.service_pages.form_subtitle', ['path' => '/'.$type]) }}</p>
        </div>
        <a
            href="{{ route($publicRouteName) }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-800"
        >
            <x-icon name="external-link" size="sm" />
            {{ __('admin.service_pages.preview_public') }}
        </a>
    </div>

    <div class="mx-auto mt-6 w-full">
        <x-admin.card :title="__('admin.service_pages.card_title')" :subtitle="__('admin.service_pages.card_subtitle')">
            <form method="POST" action="{{ route('admin.service-pages.update', ['type' => $type]) }}" class=" space-y-6">
                @csrf
                @method('PUT')
                @include('admin.service-pages._form', ['page' => $page, 'type' => $type])
                <x-admin.form-actions
                    submit-label="{{ __('save') }}"
                    submit-icon="save"
                    cancel-url="{{ route('admin.dashboard') }}"
                />
            </form>
        </x-admin.card>
    </div>
@endsection
