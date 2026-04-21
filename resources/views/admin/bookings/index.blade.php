@extends('admin.layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold tracking-tight">{{ __('Bookings') }}</h1>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                <tr>
                    <th class="px-4 py-3">{{ __('Tour') }}</th>
                    <th class="px-4 py-3">{{ __('Guest') }}</th>
                    <th class="px-4 py-3">{{ __('Date') }}</th>
                    <th class="px-4 py-3">{{ __('People') }}</th>
                    <th class="px-4 py-3">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($bookings as $booking)
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $booking->tour?->title }}</td>
                        <td class="px-4 py-3 text-slate-600">
                            <div>{{ $booking->name }}</div>
                            <div class="text-xs text-slate-500">{{ $booking->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ optional($booking->travel_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $booking->people_count }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="rounded-lg border border-slate-200 px-2 py-1 text-xs font-medium focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60">
                                    @foreach(['pending', 'confirmed', 'cancelled'] as $st)
                                        <option value="{{ $st }}" @selected($booking->status === $st)>{{ __('status.'.$st) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-lg bg-slate-900 px-2 py-1 text-xs font-semibold text-white hover:bg-slate-800">{{ __('Save') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
@endsection
