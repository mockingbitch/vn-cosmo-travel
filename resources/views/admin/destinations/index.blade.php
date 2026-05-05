@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full max-w-6xl">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('destinations') }}</h1>
                <a href="{{ route('admin.destinations.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <x-icon name="add" size="sm" />
                    {{ __('ui.add_destination') }}
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3 sm:px-6">{{ __('ui.name_en') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('ui.name_vi') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('region') }}</th>
                            <th class="px-4 py-3 sm:px-6">{{ __('slug') }}</th>
                            @if(auth()->user()->canManageUsers())
                                <th class="px-4 py-3 sm:px-6">{{ __('audit.created_by') }}</th>
                                <th class="px-4 py-3 sm:px-6">{{ __('audit.updated_by') }}</th>
                            @endif
                            <th class="px-4 py-3 text-right sm:px-6">{{ __('actions') }}</th>
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
                                @if(auth()->user()->canManageUsers())
                                    <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $destination->creator?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-slate-600 sm:px-6">{{ $destination->updatedBy?->name ?? '—' }}</td>
                                @endif
                                <td class="px-4 py-3 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-admin.action-icon
                                            :href="route('admin.destinations.edit', $destination)"
                                            icon="pencil"
                                            :title="__('edit')"
                                        />
                                        <x-admin.confirm-delete
                                            :delete-url="route('admin.destinations.destroy', $destination)"
                                            :message="__('confirm.delete_destination')"
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
                {{ $destinations->links() }}
            </div>
        </div>
    </div>
@endsection
