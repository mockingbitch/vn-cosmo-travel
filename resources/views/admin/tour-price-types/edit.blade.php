@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.price_types.edit') }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ __('admin.price_types.edit_hint') }}</p>

            <form method="POST" action="{{ route('admin.tour-price-types.update', $priceType) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                @include('admin.tour-price-types._form', ['priceType' => $priceType, 'categories' => $categories])
                <x-admin.form-actions
                    submit-label="{{ __('save') }}"
                    submit-icon="save"
                    cancel-url="{{ route('admin.tour-price-types.index') }}"
                />
            </form>
        </div>
    </div>
@endsection
