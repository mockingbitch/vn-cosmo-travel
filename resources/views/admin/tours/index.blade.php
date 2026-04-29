@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Tours') }}</h1>
                <a href="{{ route('admin.tours.create') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">{{ __('Add tour') }}</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3 sm:px-6">{{ __('Title') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Destination') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Status') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Days') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('Price') }}</th>
                            @if(auth()->user()->canManageUsers())
                                <th class="px-4 py-3 sm:px-6">{{ __('audit.created_by') }}</th>
                                <th class="px-4 py-3 sm:px-6">{{ __('audit.updated_by') }}</th>
                            @endif
                            <th class="px-4 py-3 text-right sm:px-6">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($tours as $tour)
                            <tr>
                                <td class="px-4 py-3 sm:px-6">
                                    <a
                                        href="{{ route('admin.tours.edit', $tour) }}"
                                        class="group flex items-center gap-3"
                                        title="{{ __('Edit') }}"
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
                                <td class="px-4 py-3 sm:px-6">
                                    @if($tour->status === \App\Models\Tour::STATUS_ACTIVE)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ __('status.active') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                                    @endif
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
                                            :title="__('Edit')"
                                        />
                                        <x-admin.confirm-delete
                                            :delete-url="route('admin.tours.destroy', $tour)"
                                            :message="__('confirm.delete_tour')"
                                        >
                                            <x-admin.action-icon icon="trash" variant="danger" :title="__('Delete')" />
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
