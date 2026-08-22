@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('ui.edit_destination') }}</h1>

            <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                @include('admin.destinations._form', ['destination' => $destination])
                <x-admin.form-actions
                    submit-label="{{ __('save') }}"
                    submit-icon="save"
                    cancel-url="{{ route('admin.destinations.index') }}"
                />
            </form>
        </div>
    </div>
@endsection
