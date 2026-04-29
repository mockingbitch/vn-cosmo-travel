@extends('admin.layouts.app')

@php
    /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $bookings */
    /** @var array{q?: string|null, status?: string|null, tour_id?: int|null} $filters */
    /** @var \Illuminate\Support\Collection $tours */
    $statusBadgeMap = [
        'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'confirmed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200',
    ];
@endphp

@section('content')
    <div class="grid gap-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Bookings') }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ __('Booking requests submitted from the tour pages.') }}</p>
            </div>
            <div class="text-xs font-semibold text-slate-500">
                {{ __(':total bookings', ['total' => $bookings->total()]) }}
            </div>
        </div>

        <x-admin.card>
            <form method="GET" class="grid gap-3 sm:grid-cols-12">
                <label class="sm:col-span-5">
                    <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Search') }}</span>
                    <input
                        type="search"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="{{ __('Name, email, phone, note…') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                </label>

                <label class="sm:col-span-3">
                    <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Status') }}</span>
                    <select
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >
                        <option value="">{{ __('All') }}</option>
                        @foreach(['pending', 'confirmed', 'cancelled'] as $st)
                            <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ __('status.'.$st) }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="sm:col-span-3">
                    <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Tour') }}</span>
                    <select
                        name="tour_id"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >
                        <option value="">{{ __('All') }}</option>
                        @foreach($tours as $t)
                            <option value="{{ $t->id }}" @selected((int) ($filters['tour_id'] ?? 0) === (int) $t->id)>{{ $t->title }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="flex items-end gap-2 sm:col-span-1">
                    <x-admin.button type="submit" variant="primary" class="w-full justify-center">{{ __('Filter') }}</x-admin.button>
                </div>
            </form>
        </x-admin.card>

        <x-admin.card>
            <div
                x-data="{
                    detailOpen: false,
                    detail: { id: 0, name: '', email: '', phone: '', tour: '', tourFrontendUrl: null, travelDate: '', peopleCount: 0, status: 'pending', note: '', createdAt: '', updateUrl: '' },
                    open(payload) { this.detail = payload; this.detailOpen = true; },
                }"
            >
                @forelse($bookings as $booking)
                    @if($loop->first)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    <tr>
                                        <th class="px-4 py-3">{{ __('Tour') }}</th>
                                        <th class="px-4 py-3">{{ __('Guest') }}</th>
                                        <th class="px-4 py-3">{{ __('Travel date') }}</th>
                                        <th class="px-4 py-3">{{ __('People') }}</th>
                                        <th class="px-4 py-3">{{ __('Status') }}</th>
                                        <th class="px-4 py-3">{{ __('Submitted') }}</th>
                                        <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                    @endif

                    @php
                        $statusKey = (string) $booking->status;
                        $statusClass = $statusBadgeMap[$statusKey] ?? 'bg-slate-50 text-slate-700 ring-slate-200';
                        $tourSlug = (string) ($booking->tour?->slug ?? '');
                        $tourFrontendUrl = $tourSlug !== '' ? route('tours.show', $tourSlug) : null;
                        $detailPayload = [
                            'id' => (int) $booking->id,
                            'name' => (string) $booking->name,
                            'email' => (string) $booking->email,
                            'phone' => (string) $booking->phone,
                            'tour' => (string) ($booking->tour?->title ?? ''),
                            'tourFrontendUrl' => $tourFrontendUrl,
                            'travelDate' => optional($booking->travel_date)->format('d/m/Y'),
                            'peopleCount' => (int) $booking->people_count,
                            'status' => $statusKey,
                            'note' => (string) ($booking->note ?? ''),
                            'createdAt' => optional($booking->created_at)->format('d/m/Y H:i'),
                            'updateUrl' => route('admin.bookings.update', $booking),
                        ];
                    @endphp
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 align-top">
                            @if($booking->tour && $tourFrontendUrl)
                                <a
                                    href="{{ $tourFrontendUrl }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="inline-flex items-center gap-1.5 font-medium text-slate-900 hover:text-indigo-700 hover:underline"
                                    title="{{ __('View on site') }}"
                                >
                                    <span class="line-clamp-2">{{ $booking->tour->title }}</span>
                                    <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                </a>
                            @else
                                <div class="font-medium text-slate-500">—</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top">
                            <div class="font-medium text-slate-900">{{ $booking->name }}</div>
                            <div class="text-xs text-slate-500">{{ $booking->email }}</div>
                            <div class="text-xs text-slate-500">{{ $booking->phone }}</div>
                        </td>
                        <td class="px-4 py-3 align-top text-slate-700">
                            {{ optional($booking->travel_date)->format('d/m/Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 align-top text-slate-700">{{ $booking->people_count }}</td>
                        <td class="px-4 py-3 align-top">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                {{ __('status.'.$statusKey) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 align-top text-xs text-slate-500">
                            {{ optional($booking->created_at)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 align-top text-right">
                            <div class="flex items-center justify-end">
                                <x-admin.action-icon
                                    icon="eye"
                                    :title="__('View details')"
                                    @click="open({{ \Illuminate\Support\Js::from($detailPayload) }})"
                                />
                            </div>
                        </td>
                    </tr>

                    @if($loop->last)
                                </tbody>
                            </table>
                        </div>
                    @endif
                @empty
                    <div class="grid place-items-center px-6 py-16 text-center">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div class="mt-3 text-sm font-semibold text-slate-900">{{ __('No bookings yet') }}</div>
                        <p class="mt-1 max-w-md text-xs text-slate-500">{{ __('When customers submit a booking from a tour page, it will appear here.') }}</p>
                    </div>
                @endforelse

                @if($bookings->hasPages())
                    <div class="mt-6">{{ $bookings->links() }}</div>
                @endif

                {{-- Booking detail modal --}}
                <x-admin.modal name="detailOpen" :title="__('Booking details')">
                    <div class="grid gap-5">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('Tour') }}</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900" x-text="detail.tour"></div>
                                <template x-if="detail.tourFrontendUrl">
                                    <a :href="detail.tourFrontendUrl" target="_blank" rel="noopener" class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold text-indigo-700 hover:underline">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                        {{ __('View on site') }}
                                    </a>
                                </template>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('Submitted') }}</div>
                                <div class="mt-1 text-sm text-slate-700" x-text="detail.createdAt"></div>
                            </div>
                        </div>

                        <div class="grid gap-3 rounded-2xl border border-slate-200 bg-slate-50/60 p-4 sm:grid-cols-2">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('Full name') }}</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900" x-text="detail.name"></div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('Email') }}</div>
                                <a class="mt-1 block break-all text-sm font-medium text-indigo-700 hover:underline" :href="'mailto:' + detail.email" x-text="detail.email"></a>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('Phone') }}</div>
                                <a class="mt-1 block text-sm font-medium text-indigo-700 hover:underline" :href="'tel:' + detail.phone" x-text="detail.phone"></a>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('Travel date') }}</div>
                                <div class="mt-1 text-sm text-slate-700" x-text="detail.travelDate"></div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('People') }}</div>
                                <div class="mt-1 text-sm text-slate-700" x-text="detail.peopleCount"></div>
                            </div>
                        </div>

                        <template x-if="detail.note && detail.note.length > 0">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ __('Note') }}</div>
                                <p class="mt-1 whitespace-pre-line rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-700" x-text="detail.note"></p>
                            </div>
                        </template>

                        <form method="POST" :action="detail.updateUrl" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-end">
                            @csrf
                            @method('PATCH')
                            @if(($filters['q'] ?? '') !== '') <input type="hidden" name="q" value="{{ $filters['q'] }}"> @endif
                            @if(($filters['status'] ?? '') !== '') <input type="hidden" name="status" value="{{ $filters['status'] }}"> @endif
                            @if(($filters['tour_id'] ?? 0) > 0) <input type="hidden" name="tour_id" value="{{ $filters['tour_id'] }}"> @endif
                            @if(request()->filled('page')) <input type="hidden" name="page" value="{{ request('page') }}"> @endif

                            <label class="block flex-1">
                                <span class="mb-1 block text-xs font-semibold text-slate-700">{{ __('Status') }}</span>
                                <select
                                    name="status"
                                    x-model="detail.status"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                >
                                    @foreach(['pending', 'confirmed', 'cancelled'] as $st)
                                        <option value="{{ $st }}">{{ __('status.'.$st) }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <x-admin.button type="submit" variant="primary">{{ __('Save') }}</x-admin.button>
                        </form>
                    </div>
                </x-admin.modal>
            </div>
        </x-admin.card>
    </div>
@endsection
