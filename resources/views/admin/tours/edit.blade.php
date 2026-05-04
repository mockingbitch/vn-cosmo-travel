@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Edit tour') }}</h1>
            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                <span>{{ __('Status') }}:</span>
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
                @include('admin.tours._form', ['tour' => $tour, 'destinations' => $destinations])
                <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        <x-icon name="save" size="sm" />
                        {{ __('Save') }}
                    </button>
                    <a href="{{ route('admin.tours.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <x-icon name="arrow-left" size="sm" />
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
