@php
    /** @var \App\Services\SettingsService $settings */
    $settings = app(\App\Services\SettingsService::class);
    $faviconPath = (string) ($settings->get('site.favicon_path') ?? '');
    $faviconUrl = null;
    $faviconType = null;

    if ($faviconPath !== '') {
        $faviconUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($faviconPath);
        $ext = strtolower((string) pathinfo($faviconPath, PATHINFO_EXTENSION));
        $faviconType = match ($ext) {
            'ico' => 'image/x-icon',
            'png' => 'image/png',
            'svg' => 'image/svg+xml',
            'jpg', 'jpeg' => 'image/jpeg',
            'webp' => 'image/webp',
            default => null,
        };
    }
@endphp

@if($faviconUrl)
    <link rel="icon" @if($faviconType) type="{{ $faviconType }}" @endif href="{{ $faviconUrl }}">
    <link rel="shortcut icon" @if($faviconType) type="{{ $faviconType }}" @endif href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
@else
    {{-- Fallback shipped asset when admin has not uploaded a favicon --}}
    @php $defaultIco = asset('images/default-favicon.ico'); @endphp
    <link rel="icon" type="image/x-icon" href="{{ $defaultIco }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $defaultIco }}">
@endif
