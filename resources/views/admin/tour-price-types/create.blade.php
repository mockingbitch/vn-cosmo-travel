@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.price_types.new') }}</h1>

            <form method="POST" action="{{ route('admin.tour-price-types.store') }}" class="mt-6 space-y-4">
                @csrf
                @include('admin.tour-price-types._form', ['priceType' => null, 'categories' => $categories])
                <x-admin.form-actions
                    submit-label="{{ __('create') }}"
                    submit-icon="add"
                    cancel-url="{{ route('admin.tour-price-types.index') }}"
                />
            </form>
        </div>
    </div>
@endsection
