@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold tracking-tight">{{ __('Destinations') }}</h1>
        <a href="{{ route('admin.destinations.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Add destination') }}</a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                <tr>
                    <th class="px-4 py-3">{{ __('Name') }}</th>
                    <th class="px-4 py-3">{{ __('Region') }}</th>
                    <th class="px-4 py-3">{{ __('Slug') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($destinations as $destination)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $destination->name }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            @if($destination->region)
                                {{ __('dest.region.'.$destination->region) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $destination->slug }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.destinations.edit', $destination) }}" class="font-semibold text-slate-900 hover:underline">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="inline" onsubmit="return confirm({{ \Illuminate\Support\Js::from(__('confirm.delete_destination')) }});">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 font-semibold text-rose-600 hover:underline">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $destinations->links() }}
    </div>
@endsection
