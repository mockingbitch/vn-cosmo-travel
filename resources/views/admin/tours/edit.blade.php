@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

    <div class="mx-auto w-full">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('ui.edit_tour') }}</h1>
            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                <span>{{ __('status') }}:</span>
                @if($tour->status === \App\Models\Tour::STATUS_ACTIVE)
                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ __('status.active') }}</span>
                @else
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                @endif
                <span class="text-xs text-slate-500">{{ __('tour.admin.status_hint_list') }}</span>
            </div>

            <form method="POST" action="{{ route('admin.tours.update', $tour) }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')
                @include('admin.tours._form', ['tour' => $tour, 'destinations' => $destinations, 'priceTypes' => $priceTypes])
                <x-admin.form-actions
                    submit-label="{{ __('save') }}"
                    submit-icon="save"
                    cancel-url="{{ route('admin.tours.index') }}"
                />
            </form>
        </div>
    </div>
@endsection
