@extends('admin.layouts.app')

@section('content')
    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Create hero banner') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Add a new slide to homepage hero') }}</p>
        </div>
        <a class="text-sm font-semibold text-slate-600 hover:text-slate-900" href="{{ route('admin.banners.index') }}">{{ __('Back') }}</a>
    </div>

    <form class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.banners._form', ['banner' => null])

        <div class="mt-6 flex items-center justify-end gap-3">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
            >
                <x-icon name="add" size="sm" />
                {{ __('create') }}
            </button>
        </div>
    </form>
@endsection

