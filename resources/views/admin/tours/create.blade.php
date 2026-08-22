@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('ui.new_tour') }}</h1>

            <form method="POST" action="{{ route('admin.tours.store') }}" class="mt-6 space-y-4">
                @csrf
                @include('admin.tours._form', ['tour' => null, 'destinations' => $destinations, 'priceTypes' => $priceTypes])
                <x-admin.form-actions
                    submit-label="{{ __('create') }}"
                    submit-icon="add"
                    cancel-url="{{ route('admin.tours.index') }}"
                />
            </form>
        </div>
    </div>
@endsection
