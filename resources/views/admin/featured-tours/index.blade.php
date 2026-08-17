@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl space-y-8">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.featured_tours.page_title') }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ __('admin.featured_tours.page_subtitle') }}</p>
            </div>

            @if($featuredTours->isEmpty())
                <div class="px-5 py-10 text-center sm:px-6">
                    <p class="text-sm font-medium text-slate-600">{{ __('admin.featured_tours.empty') }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ __('admin.featured_tours.empty_help') }}</p>
                </div>
            @else
                <form id="featured-tours-order-form" method="POST" action="{{ route('admin.featured-tours.update') }}">
                    @csrf
                    @method('PUT')
                </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                <tr>
                                    <th class="px-4 py-3 sm:px-6">{{ __('title') }}</th>
                                    <th class="px-4 py-3 sm:px-6">{{ __('destination') }}</th>
                                    <th class="px-4 py-3 sm:px-6">{{ __('status') }}</th>
                                    <th class="px-4 py-3 sm:px-6">{{ __('admin.featured_tours.sort') }}</th>
                                    <th class="px-4 py-3 text-right sm:px-6">{{ __('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($featuredTours as $index => $tour)
                                    <tr>
                                        <td class="px-4 py-3 sm:px-6">
                                            <input type="hidden" form="featured-tours-order-form" name="featured_tours[{{ $index }}][tour_id]" value="{{ $tour->id }}">
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
                                        <td class="px-4 py-3 text-slate-600 sm:px-6">
                                            @if($tour->status === \App\Models\Tour::STATUS_ACTIVE)
                                                <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">{{ __('status.active') }}</span>
                                            @else
                                                <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 sm:px-6">
                                            <input
                                                type="number"
                                                form="featured-tours-order-form"
                                                name="featured_tours[{{ $index }}][featured_sort]"
                                                min="0"
                                                max="9999"
                                                value="{{ old('featured_tours.'.$index.'.featured_sort', $tour->featured_sort) }}"
                                                class="w-24 rounded-lg border border-slate-200 bg-white px-2 py-1 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-300/60"
                                            />
                                        </td>
                                        <td class="px-4 py-3 text-right sm:px-6">
                                            <div class="flex items-center justify-end gap-2">
                                                <x-admin.action-icon
                                                    :href="route('admin.tours.edit', $tour)"
                                                    icon="pencil"
                                                    :title="__('edit')"
                                                />
                                                <form method="post" action="{{ route('admin.featured-tours.update-tour', $tour) }}" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_featured" value="0">
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-2 py-1 text-xs font-semibold text-rose-700 transition hover:bg-rose-100"
                                                        title="{{ __('admin.featured_tours.remove') }}"
                                                    >
                                                        {{ __('admin.featured_tours.remove') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex items-center justify-between gap-3 border-t border-slate-100 px-5 py-4 sm:px-6">
                        <p class="text-xs text-slate-500">{{ __('admin.featured_tours.sort_help') }}</p>
                        <button
                            type="submit"
                            form="featured-tours-order-form"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                        >
                            <x-icon name="save" size="sm" />
                            {{ __('admin.featured_tours.save_order') }}
                        </button>
                    </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-6">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.featured_tours.add_section_title') }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ __('admin.featured_tours.add_section_subtitle') }}</p>
            </div>

            <form method="GET" action="{{ route('admin.featured-tours.index') }}" class="grid items-end gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-4 sm:grid-cols-2 lg:grid-cols-4 sm:px-6">
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
                    <a href="{{ route('admin.featured-tours.index') }}" class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 hover:bg-slate-50">
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
                            <th class="px-4 py-3 text-right sm:px-6">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($availableTours as $tour)
                            <tr>
                                <td class="px-4 py-3 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <img
                                            src="{{ (new \App\ViewModels\TourCardViewModel($tour))->thumbnailUrl() }}"
                                            alt=""
                                            class="h-10 w-10 shrink-0 rounded-xl object-cover ring-1 ring-slate-200"
                                            loading="lazy"
                                        />
                                        <span class="font-medium text-slate-900">{{ $tour->title }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $tour->destination?->localizedName() }}</td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">
                                    @if($tour->status === \App\Models\Tour::STATUS_ACTIVE)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-semibold text-emerald-700">{{ __('status.active') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right sm:px-6">
                                    <form method="post" action="{{ route('admin.featured-tours.update-tour', $tour) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        @if($availableTours->currentPage() > 1)
                                            <input type="hidden" name="page" value="{{ $availableTours->currentPage() }}">
                                        @endif
                                        @if(!empty($filters['q']))
                                            <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                        @endif
                                        @if(!empty($filters['destination_id']))
                                            <input type="hidden" name="destination_id" value="{{ $filters['destination_id'] }}">
                                        @endif
                                        <input type="hidden" name="is_featured" value="1">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 transition hover:bg-amber-100"
                                        >
                                            <x-icon name="plus" size="sm" />
                                            {{ __('admin.featured_tours.add') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500 sm:px-6">
                                    {{ __('admin.featured_tours.no_available') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
                {{ $availableTours->links() }}
            </div>
        </div>
    </div>
@endsection
