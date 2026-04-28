@extends('admin.layouts.app')

@section('content')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold tracking-tight">{{ __('Tours') }}</h1>
        <a href="{{ route('admin.tours.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Add tour') }}</a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                <tr>
                    <th class="px-4 py-3">{{ __('Title') }}</th>
                    <th class="px-4 py-3">{{ __('Destination') }}</th>
                    <th class="px-4 py-3">{{ __('Days') }}</th>
                    <th class="px-4 py-3">{{ __('Price') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($tours as $tour)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $tour->title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $tour->destination?->localizedName() }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $tour->duration }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ number_format((int) $tour->price) }}₫</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.tours.edit', $tour) }}" class="font-semibold text-slate-900 hover:underline">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" class="inline" onsubmit="return confirm({{ \Illuminate\Support\Js::from(__('confirm.delete_tour')) }});">
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
        {{ $tours->links() }}
    </div>
@endsection
