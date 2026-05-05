@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('tours') }}</h1>
                <a href="{{ route('admin.tours.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <x-icon name="add" size="sm" />
                    {{ __('ui.add_tour') }}
                </a>
            </div>
            <form method="GET" action="{{ route('admin.tours.index') }}" class="grid items-end gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-4 lg:grid-cols-5 sm:px-6">
                <label class="grid gap-1 lg:col-span-2">
                    <span class="text-xs font-semibold text-slate-700">{{ __('ui.filter_keyword_label') }}</span>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="{{ __('ui.filter_placeholder_tours') }}"
                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    />
                </label>
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('status') }}</span>
                    <select
                        name="status"
                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >
                        <option value="">{{ __('all') }}</option>
                        <option value="{{ \App\Models\Tour::STATUS_ACTIVE }}" @selected(($filters['status'] ?? '') === \App\Models\Tour::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                        <option value="{{ \App\Models\Tour::STATUS_DISABLED }}" @selected(($filters['status'] ?? '') === \App\Models\Tour::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                    </select>
                </label>
                <label class="grid gap-1">
                    <span class="text-xs font-semibold text-slate-700">{{ __('destination') }}</span>
                    <select
                        name="destination_id"
                        class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                    >
                        <option value="">{{ __('all') }}</option>
                        @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}" @selected((string) ($filters['destination_id'] ?? '') === (string) $destination->id)>{{ $destination->localizedName() }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white hover:bg-slate-800">
                        <x-icon name="search" size="sm" />
                        {{ __('filter') }}
                    </button>
                    <a href="{{ route('admin.tours.index') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <x-icon name="close" size="sm" />
                        {{ __('ui.clear_filter') }}
                    </a>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3 sm:px-6">{{ __('title') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('destination') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('status') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('days') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('price') }}</th>
                            @if(auth()->user()->canManageUsers())
                                <th class="px-4 py-3 sm:px-6">{{ __('audit.created_by') }}</th>
                                <th class="px-4 py-3 sm:px-6">{{ __('audit.updated_by') }}</th>
                            @endif
                            <th class="px-4 py-3 text-right sm:px-6">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($tours as $tour)
                            <tr>
                                <td class="px-4 py-3 sm:px-6">
                                    <a
                                        href="{{ route('admin.tours.edit', $tour) }}"
                                        class="group flex items-center gap-3"
                                        title="{{ __('edit') }}"
                                    >
                                        <img
                                            src="{{ (new \App\ViewModels\TourCardViewModel($tour))->thumbnailUrl() }}"
                                            alt=""
                                            class="h-10 w-10 shrink-0 rounded-xl object-cover ring-1 ring-slate-200 transition group-hover:ring-slate-300"
                                            loading="lazy"
                                        />
                                        <span class="font-medium text-slate-900 group-hover:underline">{{ $tour->title }}</span>
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $tour->destination?->localizedName() }}</td>
                                <td class="px-4 py-3 align-middle sm:px-6">
                                    <form method="post" action="{{ route('admin.tours.update-status', $tour) }}" class="inline-block min-w-[9rem]">
                                        @csrf
                                        @method('PATCH')
                                        @if($tours->currentPage() > 1)
                                            <input type="hidden" name="page" value="{{ $tours->currentPage() }}">
                                        @endif
                                        @if(!empty($filters['q']))
                                            <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                        @endif
                                        @if(!empty($filters['status']))
                                            <input type="hidden" name="status" value="{{ $filters['status'] }}">
                                        @endif
                                        @if(!empty($filters['destination_id']))
                                            <input type="hidden" name="destination_id" value="{{ $filters['destination_id'] }}">
                                        @endif
                                        <label class="sr-only" for="tour-status-{{ $tour->id }}">{{ __('status') }}</label>
                                        <select
                                            id="tour-status-{{ $tour->id }}"
                                            name="status"
                                            class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-medium text-slate-800 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                            onchange="this.form.requestSubmit()"
                                        >
                                            <option value="{{ \App\Models\Tour::STATUS_ACTIVE }}" @selected($tour->status === \App\Models\Tour::STATUS_ACTIVE)>{{ __('status.active') }}</option>
                                            <option value="{{ \App\Models\Tour::STATUS_DISABLED }}" @selected($tour->status === \App\Models\Tour::STATUS_DISABLED)>{{ __('status.disabled') }}</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $tour->duration }}</td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ number_format((int) $tour->price) }}₫</td>
                                @if(auth()->user()->canManageUsers())
                                    <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $tour->creator?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $tour->updatedBy?->name ?? '—' }}</td>
                                @endif
                                <td class="px-4 py-3 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-admin.action-icon
                                            :href="route('admin.tours.edit', $tour)"
                                            icon="pencil"
                                            :title="__('edit')"
                                        />
                                        <x-admin.confirm-delete
                                            :delete-url="route('admin.tours.destroy', $tour)"
                                            :message="__('confirm.delete_tour')"
                                        >
                                            <x-admin.action-icon icon="trash" variant="danger" :title="__('delete')" />
                                        </x-admin.confirm-delete>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
                {{ $tours->links() }}
            </div>
        </div>
    </div>
@endsection
