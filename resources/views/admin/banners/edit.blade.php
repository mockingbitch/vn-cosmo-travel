@extends('admin.layouts.app')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-3">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('Hero Banner') }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ __('Edit banner copy in Vietnamese and English.') }}</p>
        </div>

        <div class="lg:col-span-2">
            <form class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" method="POST" action="{{ route('admin.banners.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('admin.banners._form', ['banner' => $banner])

                <div class="mt-6 flex items-center justify-end gap-3">
                    <x-admin.button type="submit" variant="primary">
                        <x-icon name="save" size="sm" />
                        {{ __('Save changes') }}
                    </x-admin.button>
                </div>
            </form>
        </div>

        <div>
            <x-admin.card :title="__('History')" :subtitle="__('Previous versions')">
                <div
                    class="grid max-w-full gap-3"
                    x-data="{
                        previewOpen: false,
                        preview: {
                            id: null,
                            title: { vi: '', en: '' },
                            subtitle: { vi: '', en: '' },
                            cta: { vi: '', en: '' },
                            ctaLink: '',
                            archivedAt: '',
                            imageUrl: null,
                        },
                        openPreview(payload) {
                            this.preview = payload;
                            this.previewOpen = true;
                        },
                    }"
                >
                    @forelse($history as $h)
                        @php
                            $uiLocale = app()->getLocale();
                            $titleVi = $h->getTitleForLocale('vi');
                            $titleEn = $h->getTitleForLocale('en');
                            $subtitleVi = $h->getSubtitleForLocale('vi');
                            $subtitleEn = $h->getSubtitleForLocale('en');
                            $ctaVi = $h->getCtaTextForLocale('vi');
                            $ctaEn = $h->getCtaTextForLocale('en');
                            $archivedAt = optional($h->archived_at ?? $h->created_at)?->locale($uiLocale)->isoFormat('LLL');
                            $imageUrl = $h->media?->url();
                        @endphp
                        <button
                            type="button"
                            class="cursor-pointer max-w-full overflow-hidden rounded-2xl border border-slate-200 bg-white/60 p-3 text-left transition hover:bg-white hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-200/60"
                            @click="openPreview({
                                id: {{ (int) $h->id }},
                                title: {
                                    vi: {{ \Illuminate\Support\Js::from($titleVi) }},
                                    en: {{ \Illuminate\Support\Js::from($titleEn) }},
                                },
                                subtitle: {
                                    vi: {{ \Illuminate\Support\Js::from($subtitleVi ?? '') }},
                                    en: {{ \Illuminate\Support\Js::from($subtitleEn ?? '') }},
                                },
                                cta: {
                                    vi: {{ \Illuminate\Support\Js::from($ctaVi ?? '') }},
                                    en: {{ \Illuminate\Support\Js::from($ctaEn ?? '') }},
                                },
                                ctaLink: {{ \Illuminate\Support\Js::from($h->cta_link ?? '') }},
                                archivedAt: {{ \Illuminate\Support\Js::from($archivedAt ?? '') }},
                                imageUrl: {{ \Illuminate\Support\Js::from($imageUrl) }},
                            })"
                        >
                            <div class="grid gap-3 text-left lg:grid-cols-2 lg:gap-4">
                                <div class="min-w-0 lg:border-r lg:border-slate-100 lg:pr-4">
                                    <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ __('vietnamese') }}</div>
                                    <div class="mt-1 truncate text-sm font-semibold text-slate-900">{{ $titleVi }}</div>
                                    @if(filled($subtitleVi))
                                        <div class="mt-1 line-clamp-2 break-words text-sm text-slate-600">{{ $subtitleVi }}</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ __('english') }}</div>
                                    <div class="mt-1 truncate text-sm font-semibold text-slate-900">{{ $titleEn }}</div>
                                    @if(filled($subtitleEn))
                                        <div class="mt-1 line-clamp-2 break-words text-sm text-slate-600">{{ $subtitleEn }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3 truncate text-xs font-semibold text-slate-500">
                                {{ $archivedAt }}
                            </div>
                        </button>
                    @empty
                        <div class="text-sm text-slate-500">{{ __('No history yet') }}</div>
                    @endforelse

                    <x-admin.modal name="previewOpen" :title="__('Preview version')">
                        <div class="grid gap-4">
                            <template x-if="preview.imageUrl">
                                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                    <img :src="preview.imageUrl" class="max-h-48 w-full object-cover" alt="" />
                                </div>
                            </template>

                            <div class="grid gap-4 lg:grid-cols-2 lg:gap-6">
                                <div class="grid gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="border-b border-slate-200/80 pb-2 text-xs font-semibold text-slate-600">{{ __('vietnamese') }}</div>
                                    <div>
                                        <div class="text-xs font-semibold text-slate-500">{{ __('title') }}</div>
                                        <div class="mt-1 text-sm font-semibold text-slate-900" x-text="preview.title.vi"></div>
                                    </div>
                                    <template x-if="preview.subtitle && preview.subtitle.vi && preview.subtitle.vi.length">
                                        <div>
                                            <div class="text-xs font-semibold text-slate-500">{{ __('Subtitle') }}</div>
                                            <div class="mt-1 text-sm text-slate-700" x-text="preview.subtitle.vi"></div>
                                        </div>
                                    </template>
                                    <template x-if="preview.cta && preview.cta.vi && preview.cta.vi.length">
                                        <div>
                                            <div class="text-xs font-semibold text-slate-500">{{ __('CTA text') }}</div>
                                            <div class="mt-1 text-sm text-slate-700" x-text="preview.cta.vi"></div>
                                        </div>
                                    </template>
                                </div>
                                <div class="grid gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="border-b border-slate-200/80 pb-2 text-xs font-semibold text-slate-600">{{ __('english') }}</div>
                                    <div>
                                        <div class="text-xs font-semibold text-slate-500">{{ __('title') }}</div>
                                        <div class="mt-1 text-sm font-semibold text-slate-900" x-text="preview.title.en"></div>
                                    </div>
                                    <template x-if="preview.subtitle && preview.subtitle.en && preview.subtitle.en.length">
                                        <div>
                                            <div class="text-xs font-semibold text-slate-500">{{ __('Subtitle') }}</div>
                                            <div class="mt-1 text-sm text-slate-700" x-text="preview.subtitle.en"></div>
                                        </div>
                                    </template>
                                    <template x-if="preview.cta && preview.cta.en && preview.cta.en.length">
                                        <div>
                                            <div class="text-xs font-semibold text-slate-500">{{ __('CTA text') }}</div>
                                            <div class="mt-1 text-sm text-slate-700" x-text="preview.cta.en"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <template x-if="preview.ctaLink && preview.ctaLink.length">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <div class="text-xs font-semibold text-slate-500">{{ __('CTA link') }}</div>
                                    <div class="mt-1 break-all text-sm font-medium text-slate-800" x-text="preview.ctaLink"></div>
                                </div>
                            </template>

                            <template x-if="preview.archivedAt && preview.archivedAt.length">
                                <div class="text-xs font-semibold text-slate-500" x-text="preview.archivedAt"></div>
                            </template>

                            <div class="flex items-center justify-end gap-2">
                                <x-admin.button variant="ghost" type="button" @click="previewOpen = false">
                                    <x-icon name="close" size="sm" />
                                    {{ __('Close') }}
                                </x-admin.button>

                                <form method="POST" :action="'{{ url('/admin/banners/apply') }}/' + preview.id" onsubmit="return confirm('{{ __('Apply this version?') }}')">
                                    @csrf
                                    <x-admin.button variant="primary" type="submit">
                                        <x-icon name="check" size="sm" />
                                        {{ __('apply') }}
                                    </x-admin.button>
                                </form>
                            </div>
                        </div>
                    </x-admin.modal>
                </div>
            </x-admin.card>
        </div>
    </div>
@endsection

