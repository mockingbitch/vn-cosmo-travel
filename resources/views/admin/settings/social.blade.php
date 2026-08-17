@extends('admin.layouts.app')

@section('content')
    @php
        $storedLinks = $settings['social.links'] ?? null;
        $initialLinks = [];

        if (is_array($storedLinks)) {
            foreach ($storedLinks as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $initialLinks[] = [
                    'label' => trim((string) ($row['label'] ?? '')),
                    'url' => trim((string) ($row['url'] ?? '')),
                ];
            }
        }

        if ($initialLinks === []) {
            $legacy = [
                'facebook' => 'Facebook',
                'instagram' => 'Instagram',
                'youtube' => 'YouTube',
                'tiktok' => 'TikTok',
            ];
            foreach ($legacy as $key => $label) {
                $url = trim((string) ($settings['social.'.$key] ?? ''));
                if ($url !== '') {
                    $initialLinks[] = ['label' => $label, 'url' => $url];
                }
            }
        }
    @endphp

    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ __('admin.settings.social.page_title') }}</h1>
        <p class="mt-1 text-sm text-slate-600">{{ __('admin.settings.social.page_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.social.update') }}" class="mt-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <x-admin.link-list-editor
                name="social_links"
                :label="__('admin.settings.social.section_title')"
                :help="__('admin.settings.social.section_help')"
                :links="$initialLinks"
            />
        </div>

        <div class="mt-10 flex items-center justify-end gap-3 pt-2 sm:pt-3">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400/60 focus:ring-offset-2"
            >
                <x-icon name="save" size="sm" />
                {{ __('admin.settings.save_changes') }}
            </button>
        </div>
    </form>
@endsection
