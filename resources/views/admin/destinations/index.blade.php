@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Destinations') }}</h1>
                <a href="{{ route('admin.destinations.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Add destination') }}</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3 sm:px-6">{{ __('Name (EN)') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Name (VI)') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Region') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Slug') }}</th>
                            <th class="px-4 py-3 text-right sm:px-6">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($destinations as $destination)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900 sm:px-6">{{ $destination->name_en }}</td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $destination->name_vi }}</td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">
                                    @if($destination->region)
                                        {{ __('dest.region.'.$destination->region) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $destination->slug }}</td>
                                <td class="px-4 py-3 text-right sm:px-6">
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

            <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
                {{ $destinations->links() }}
            </div>
        </div>
    </div>
@endsection
