@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold tracking-tight">{{ __('New tour') }}</h1>

    <form method="POST" action="{{ route('admin.tours.store') }}" class="mt-6 max-w-2xl space-y-4">
        @csrf
        @include('admin.tours._form', ['tour' => null, 'destinations' => $destinations])
        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Create') }}</button>
            <a href="{{ route('admin.tours.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">{{ __('Cancel') }}</a>
        </div>
    </form>
@endsection
