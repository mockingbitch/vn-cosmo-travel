@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('ui.edit_destination') }}</h1>

            <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                @include('admin.destinations._form', ['destination' => $destination])
                <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        <x-icon name="save" size="sm" />
                        {{ __('save') }}
                    </button>
                    <a href="{{ route('admin.destinations.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <x-icon name="arrow-left" size="sm" />
                        {{ __('cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
