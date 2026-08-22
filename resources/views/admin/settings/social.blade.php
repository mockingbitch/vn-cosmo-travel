@extends('admin.layouts.app')

@section('content')
    <x-admin.error-summary />

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

        <x-admin.form-actions
            submit-label="{{ __('admin.settings.save_changes') }}"
            submit-icon="save"
        />
    </form>
@endsection
