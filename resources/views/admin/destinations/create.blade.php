@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('New destination') }}</h1>

            <form method="POST" action="{{ route('admin.destinations.store') }}" class="mt-6 space-y-4">
                @csrf
                @include('admin.destinations._form', ['destination' => null])
                <div class="flex flex-wrap gap-3 border-t border-slate-100 pt-6">
                    <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Create') }}</button>
                    <a href="{{ route('admin.destinations.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
