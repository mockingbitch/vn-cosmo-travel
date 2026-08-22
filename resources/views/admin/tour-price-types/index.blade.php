@extends('admin.layouts.app')

@section('content')
    <div class="mx-auto w-full">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.price_types.title') }}</h1>
                    <p class="mt-1 text-sm text-slate-600">{{ __('admin.price_types.intro') }}</p>
                </div>
                <a href="{{ route('admin.tour-price-types.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    <x-icon name="add" size="sm" />
                    {{ __('admin.price_types.new') }}
                </a>
            </div>

            @if($errors->any())
                <div class="border-b border-rose-100 bg-rose-50 px-5 py-3 text-sm text-rose-700 sm:px-6">
                    @foreach($errors->all() as $message)
                        <div>{{ $message }}</div>
                    @endforeach
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <th scope="col"ead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th scope="col" class="px-4 py-3 sm:px-6">{{ __('admin.price_types.name') }}</th>
                            <th scope="col" class="px-4 py-3 sm:px-6">{{ __('admin.price_types.category') }}</th>
                            <th scope="col" class="px-4 py-3 sm:px-6">{{ __('admin.price_types.sort_order') }}</th>
                            <th scope="col" class="px-4 py-3 sm:px-6">{{ __('status') }}</th>
                            <th scope="col" class="px-4 py-3 sm:px-6">{{ __('admin.price_types.usage') }}</th>
                            <th scope="col" class="px-4 py-3 text-right sm:px-6">{{ __('actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($priceTypes as $priceType)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-900 sm:px-6">{{ $priceType->name }}</td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">
                                    {{ filled($priceType->category) ? $priceType->category : '—' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 tabular-nums sm:px-6">{{ $priceType->sort_order }}</td>
                                <td class="px-4 py-3 sm:px-6">
                                    @if($priceType->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">{{ __('status.active') }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">{{ __('status.disabled') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600 sm:px-6">
                                    {{ __('admin.price_types.used_by_tours', ['count' => $priceType->prices_count]) }}
                                </td>
                                <td class="px-4 py-3 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-admin.action-icon
                                            :href="route('admin.tour-price-types.edit', $priceType)"
                                            icon="pencil"
                                            :title="__('edit')"
                                        />
                                        @if($priceType->prices_count === 0)
                                            <x-admin.confirm-delete
                                                :delete-url="route('admin.tour-price-types.destroy', $priceType)"
                                                :message="__('confirm.delete_tour_price_type')"
                                                :item-name="$priceType->name"
                                            >
                                                <x-admin.action-icon icon="trash" variant="danger" :title="__('delete')" />
                                            </x-admin.confirm-delete>
                                        @else
                                            <span class="text-xs text-slate-500" title="{{ __('flash.tour_price_type.in_use') }}">{{ __('admin.price_types.locked') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500 sm:px-6">
                                    {{ __('admin.price_types.empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
                {{ $priceTypes->links() }}
            </div>
        </div>
    </div>
@endsection
