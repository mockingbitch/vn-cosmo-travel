@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $pageTitle }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('admin.service_pages.form_subtitle', ['path' => '/'.$type]) }}</p>
        </div>
        <a
            href="{{ route($publicRouteName) }}"
            class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-800"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
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
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Save') }}</button>
                    <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</a>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection
