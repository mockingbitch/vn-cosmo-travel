@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
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

    <div class="mx-auto mt-6 w-full max-w-6xl">
        <x-admin.card :title="__('admin.service_pages.card_title')" :subtitle="__('admin.service_pages.card_subtitle')">
            <form method="POST" action="{{ route('admin.service-pages.update', ['type' => $type]) }}" class="max-w-5xl space-y-6">
                @csrf
                @method('PUT')
                @include('admin.service-pages._form', ['page' => $page, 'type' => $type])
                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        <x-icon name="save" size="sm" />
                        {{ __('save') }}
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <x-icon name="arrow-left" size="sm" />
                        {{ __('cancel') }}
                    </a>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
