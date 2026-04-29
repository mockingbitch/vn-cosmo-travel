@extends('admin.layouts.app')

@section('content')
    <div class="flex items-end justify-between gap-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Hero banners') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Manage homepage hero carousel') }}</p>
        </div>
        <a
            href="{{ route('admin.banners.create') }}"
            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
        >
            {{ __('Create') }}
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                <tr>
                    <th class="px-4 py-3">{{ __('Banner') }}</th>
                    <th class="px-4 py-3">{{ __('Order') }}</th>
                    <th class="px-4 py-3">{{ __('Status') }}</th>
                    <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($banners as $banner)
                    <tr class="align-top">
                        <td class="px-4 py-4">
                            <div class="flex items-start gap-3">
                                <div class="h-14 w-24 overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                    @if($banner->image_path)
                                        <img class="h-full w-full object-cover" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image_path) }}" alt="" />
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-900">{{ $banner->title }}</div>
                                    @if($banner->subtitle)
                                        <div class="mt-1 text-slate-600">{{ $banner->subtitle }}</div>
                                    @endif
                                    @if($banner->cta_text)
                                        <div class="mt-2 text-xs font-semibold text-slate-500">{{ __('CTA') }}: {{ $banner->cta_text }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-semibold text-slate-700">{{ $banner->sort_order }}</td>
                        <td class="px-4 py-4">
                            @if($banner->is_active)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">{{ __('Active') }}</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <x-admin.action-icon
                                    :href="route('admin.banners.edit', $banner)"
                                    icon="pencil"
                                    :title="__('Edit')"
                                />
                                <x-admin.confirm-delete
                                    :delete-url="route('admin.banners.destroy', $banner)"
                                    :message="__('Are you sure?')"
                                >
                                    <x-admin.action-icon icon="trash" variant="danger" :title="__('Delete')" />
                                </x-admin.confirm-delete>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-slate-500">{{ __('No banners yet') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $banners->links() }}
    </div>
@endsection

