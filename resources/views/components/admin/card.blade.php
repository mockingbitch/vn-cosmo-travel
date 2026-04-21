@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm']) }}>
    @if($title)
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="text-sm font-semibold text-slate-900">{{ $title }}</div>
                @if($subtitle)
                    <div class="mt-1 text-sm text-slate-600">{{ $subtitle }}</div>
                @endif
            </div>
            @if(trim((string) ($actions ?? '')) !== '')
                <div class="shrink-0">
                    {{ $actions }}
                </div>
            @endif
        </div>
        <div class="mt-4">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>

